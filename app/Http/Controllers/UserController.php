<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserWarehouse;

use Illuminate\Http\Request;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Helpers\JWTToken;
use App\Models\ActivityLog;

class UserController extends Controller
{

    public function create()
    {
        $warehouses = Warehouse::whereNull('deleted_at')->get();
        return view('users.user_add', compact('warehouses'));
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:11|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
            'work_areas' => 'required|array',
            'work_areas.*' => 'exists:warehouses,id',
        ], [
            'name.required' => 'İsim girmek zorunludur.',
            'phone_number.min' => 'Telefon numarası en az 11 karakter olmalıdır.',
            'phone_number.required' => 'Telefon numarası girmek zorunludur.',
            'email.unique' => 'Bu e-posta adresi zaten kullanımda.',
            'email.required' => 'Email girmek zorunludur.',
            'password.required' => 'Şifre girmek zorunludur.',
            'password.min' => 'Şifre minimum 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifreler uyuşmuyor.', // Değişiklik burada
            'role.required' => 'Görev seçmek zorunludur.',
            'work_areas.required' => 'En az bir çalışma alanı seçmelisiniz.',
            'work_areas.*.exists' => 'Seçilen çalışma alanı geçerli değil.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errors" => $validator->errors()
            ], 422);
        }

        try {
            $now = now();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            
            $user->assignRole($request->role);
            $user->givePermissionTo('edit articles');

            $user->warehouses()->attach($request->work_areas, ['created_at' => $now, 'updated_at' => $now]);

            // Aktivite kaydı oluştur
            try {
                ActivityLog::create([
                    'action' => 'create',
                    'model' => 'User',
                    'model_id' => $user->id,
                    'user_id' => auth()->id(),
                    'description' => 'Yeni kullanıcı oluşturuldu: ' . $user->name,
                ]);
            } catch (\Throwable $logError) {
                \Log::error('Activity log oluşturulamadı: ' . $logError->getMessage());
            }

            return $this->sendSuccess("Kullanıcı oluşturuldu.", $user);
        } catch (\Throwable $th) {
            // Hata mesajını logla
            \Log::error('Kullanıcı oluşturulamadı: ' . $th->getMessage());
            return $this->sendError("Kullanıcı oluşturulamadı.", 500, $th->getMessage());
        }

    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if($user){
            $user->deleted_at = now();
            $user->save();
            return redirect()->route('users.user_list')->with('success', 'Kullanıcı başarıyla silindi.');
        }else{
            return redirect()->route('users.user_list')->with('error', 'Kullanıcı bulunamadı.');
        }
    }

    
  

    /**
     * Summary of getUser
     * @param \Illuminate\Http\Request $request
     * @return Controller::sendSuccess| Controller::sendError
     * - on success, it returns the user profile object.
     * - on failure, it response with an error message.
     */
    function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->sendSuccess('User profile fetched successfully.', $user);
        } catch (\Throwable $th) {
            return $this->sendError('User profile failed', 200, $th->getMessage());
        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $response = Password::sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => __($response)])
            : response()->json(['message' => __($response)], 500);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );
        return $response === Password::PASSWORD_RESET ? response()->json(['messages' => __($response)]) : response()->json(['messages' => __($response)], 500);

    }

    public function index()
    {
        // Kullanıcı listesi
        try {
            $users = User::with(['warehouses' => function ($query) {
                $query->whereNull('deleted_at');
            }])
                ->whereNull('deleted_at')
                ->get();
            
            return view('users.user_list', compact('users'));
        } catch (\Throwable $th) {
            return $this->sendError("Kullanıcı listesi alınamadı", 500, $th->getMessage());
        }
    }

    public function show($id)
    {
        // Kullanıcı bilgileri gösterme
        try {
            $user = User::with(['warehouses', 'userWarehouseModel.warehouses'])->findOrFail($id); 
            return $this->sendSuccess("Kullanıcı bilgileri", $user);
        } catch (\Throwable $th) {
            return $this->sendError("Kullanıcı bulunamadı", 404, $th->getMessage());
        }
    }

    public function showUserWarehouses($userId)
    {
        // Kullanıcının çalışma alanları
        try {
            $user = User::with(['warehouses' => function ($query) {
                $query->whereNull('deleted_at');
            }])->findOrFail($userId);
            return $this->sendSuccess("Kullanıcı bilgileri ve depoları", $user);
        } catch (\Throwable $th) {
            return $this->sendError("Kullanıcı ve depolar getirilemedi", 500, $th->getMessage());
        }
    }
    public function edit($id)
    {
        $user = User::with(['warehouses' => function ($query) {
            $query->whereNull('deleted_at');
        }])->findOrFail($id); // Kullanıcıyı bul
            
        return view('users.user_edit', compact('user')); // View'a gönder
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Kullanıcıyı bul

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:11|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // Şifre isteğe bağlı hale getirildi
            'role' => 'required|string',
            'work_areas' => 'nullable|array', // work_areas isteğe bağlı hale getirildi
            'work_areas.*' => 'exists:warehouses,id',
        ], [
            'name.required' => 'İsim girmek zorunludur.',
            'phone_number.min' => 'Telefon numarası en az 11 karakter olmalıdır.',
            'phone_number.required' => 'Telefon numarası girmek zorunludur.',
            'email.unique' => 'Bu e-posta adresi zaten kullanımda.',
            'email.required' => 'Email girmek zorunludur.',
            'password.required' => 'Şifre girmek zorunludur.',
            'password.min' => 'Şifre minimum 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifreler uyuşmuyor.',
            'role.required' => 'Görev seçmek zorunludur.',
            'work_areas.required' => 'En az bir çalışma alanı seçmelisiniz.',
            'work_areas.*.exists' => 'Seçilen çalışma alanı geçerli değil.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); // Hataları geri döndür
        }

        try {
            $user->update($validator->validated());

            // Eğer work_areas boşsa, mevcut depoları sil
            if ($request->has('work_areas')) {
                $user->warehouses()->sync($request->work_areas); // Depoları güncelle
            } else {
                $user->warehouses()->sync([]); // Depoları temizle
            }

            // Aktivite kaydı oluştur
            ActivityLog::create([
                'action' => 'update',
                'model' => 'User',
                'model_id' => $user->id,
                'user_id' => auth()->id(),
                'description' => 'Kullanıcı bilgileri güncellendi: ' . $user->name,
            ]);

            return redirect()->route('user-edit', $user->id)->with('success', 'Kullanıcı başarıyla güncellendi.'); // Başarı mesajı ile yönlendir
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Kullanıcı güncellenirken bir hata oluştu.')->withInput();
        }
    }

    public function delete($id)
    {
        // Kullanıcı silme işlemleri
        try {
            $user = User::findOrFail($id);
            $user->warehouses()->detach();
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kullanıcı başarıyla silindi.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı bulunamadı.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı silinirken bir hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        // Şifre güncelleme işlemleri
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:10', 'max:20'],
            'current_password' => ['nullable', 'string'],
            'password' => ['required_with:current_password', 'nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı bulunamadı.'
            ], 404);
        }

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mevcut şifre yanlış.'
                ], 422);
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Yeni şifre girilmedi.'
                ], 422);
            }
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Bilgileriniz başarıyla güncellendi.'
        ]);
    }

    // Yeni eklenen store metodu
    public function store(Request $request)
    {
        // Validasyon kuralları
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:11|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
            'work_areas' => 'required|array',
            'work_areas.*' => 'exists:warehouses,id',
        ], [
            'name.required' => 'İsim girmek zorunludur.',
            'phone_number.min' => 'Telefon numarası en az 11 karakter olmalıdır.',
            'phone_number.required' => 'Telefon numarası girmek zorunludur.',
            'email.unique' => 'Bu e-posta adresi zaten kullanımda.',
            'email.required' => 'Email girmek zorunludur.',
            'password.required' => 'Şifre girmek zorunludur.',
            'password.min' => 'Şifre minimum 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifreler uyuşmuyor.',
            'role.required' => 'Görev seçmek zorunludur.',
            'work_areas.required' => 'En az bir çalışma alanı seçmelisiniz.',
            'work_areas.*.exists' => 'Seçilen çalışma alanı geçerli değil.',
        ]);

        // Eğer validasyon hatalıysa, geri yönlendir ve hataları göster
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kullanıcıyı veritabanına kaydetme işlemleri
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'password' => Hash::make($request->password),  // Şifreyi hash'le
        ]);

        // Kullancının çalışma alanlarını ilişkilendir
        $user->warehouses()->attach($request->work_areas);

        // Başarılı olursa, kullanıcı listesini göster ve başarı mesajı ile yönlendir
        return redirect()->route('users.user_list')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }
}
