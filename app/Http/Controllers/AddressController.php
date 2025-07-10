<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    /**
     * Display a listing of the user's addresses.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $addresses = Addresses::where('user_id', $user->id)
                             ->orderBy('is_default', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->get();

        return response()->json([
            'success' => true,
            'data' => $addresses
        ]);
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'label' => 'required|string|max:100',
            'recipient_name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean'
        ]);

        DB::beginTransaction();

        try {
            // If this is set as default, remove default from other addresses
            if ($request->is_default) {
                Addresses::where('user_id', $user->id)
                         ->where('is_default', true)
                         ->update(['is_default' => false]);
            }

            // If this is the first address for the user, make it default
            $existingAddressesCount = Addresses::where('user_id', $user->id)->count();
            $isDefault = $request->is_default || $existingAddressesCount === 0;

            $address = Addresses::create([
                'user_id' => $user->id,
                'label' => $request->label,
                'recipient_name' => $request->recipient_name,
                'phone_number' => $request->phone_number,
                'address_line' => $request->address_line,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'is_default' => $isDefault
            ]);

            DB::commit();

            Log::info('New address created', [
                'user_id' => $user->id,
                'address_id' => $address->id,
                'label' => $address->label
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil ditambahkan',
                'data' => $address
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create address', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $address = Addresses::where('id', $id)
                           ->where('user_id', $user->id)
                           ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'label' => 'required|string|max:100',
            'recipient_name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean'
        ]);

        DB::beginTransaction();

        try {
            // If this is set as default, remove default from other addresses
            if ($request->is_default && !$address->is_default) {
                Addresses::where('user_id', $user->id)
                         ->where('is_default', true)
                         ->update(['is_default' => false]);
            }

            $address->update([
                'label' => $request->label,
                'recipient_name' => $request->recipient_name,
                'phone_number' => $request->phone_number,
                'address_line' => $request->address_line,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'is_default' => $request->is_default ?? $address->is_default
            ]);

            DB::commit();

            Log::info('Address updated', [
                'user_id' => $user->id,
                'address_id' => $address->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil diperbarui',
                'data' => $address
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update address', [
                'user_id' => $user->id,
                'address_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $address = Addresses::where('id', $id)
                           ->where('user_id', $user->id)
                           ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $wasDefault = $address->is_default;
            $address->delete();

            // If deleted address was default, set another address as default
            if ($wasDefault) {
                $newDefaultAddress = Addresses::where('user_id', $user->id)
                                             ->orderBy('created_at', 'desc')
                                             ->first();
                
                if ($newDefaultAddress) {
                    $newDefaultAddress->update(['is_default' => true]);
                }
            }

            DB::commit();

            Log::info('Address deleted', [
                'user_id' => $user->id,
                'address_id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to delete address', [
                'user_id' => $user->id,
                'address_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus alamat: ' . $e->getMessage()
            ], 500);
        }
    }
}
