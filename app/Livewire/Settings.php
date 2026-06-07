<?php

namespace App\Livewire;

use App\Imports\BudgetSpendsImport;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Settings extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public string $currentPassword = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $deletePassword = '';

    public bool $hasPassword = false;

    public ?TemporaryUploadedFile $importFile = null;

    public function mount(): void
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->hasPassword = filled($user->getAuthPassword());
    }

    public function updateProfile(): void
    {
        $user = auth()->user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $emailChanged = $validated['email'] !== $user->email;

        $user->forceFill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
        ])->save();

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
        }

        $this->dispatch(
            'alokasi-flash',
            tone: 'success',
            title: 'Berhasil',
            message: $emailChanged
                ? 'Profile updated. Verification email sudah dikirim ke alamat baru.'
                : 'Profile updated.',
        );
    }

    public function updatePassword(): void
    {
        $wasPasswordless = ! $this->hasPassword;
        $rules = [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($this->hasPassword) {
            $rules['currentPassword'] = ['required', 'current_password'];
        }

        $validated = $this->validate($rules);

        auth()->user()->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        $this->reset(['currentPassword', 'password', 'password_confirmation']);
        $this->hasPassword = true;

        $this->dispatch(
            'alokasi-flash',
            tone: 'success',
            title: 'Berhasil',
            message: $wasPasswordless ? 'Password berhasil dibuat.' : 'Password updated.',
        );
    }

    public function importData(): void
    {
        $this->validate([
            'importFile' => ['required', 'file', 'mimes:csv,txt,xlsx,ods', 'max:5120'],
        ]);

        Excel::import(new BudgetSpendsImport(auth()->user()), $this->importFile);

        $this->reset('importFile');
        $this->dispatch('alokasi-flash', tone: 'success', title: 'Berhasil', message: 'Import finished.');
    }

    public function deleteAccount()
    {
        $this->validate([
            'deletePassword' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        Auth::guard('web')->logout();
        $user->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Account deleted.');
    }

    public function render()
    {
        return view('livewire.settings', [
            'budgets' => Budget::where('user_id', auth()->id())
                ->withCount('spends')
                ->orderBy('name')
                ->get(['id', 'name', 'income']),
        ]);
    }
}
