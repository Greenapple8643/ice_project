<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Pages\Auth\Login as BaseAuth;
use App\Models\User;

class Login extends BaseAuth
{
    public function mount(): void
    {
        parent::mount();
        if (app()->environment('local')) {
            $this->form->fill([
                'email' => User::getDefaultUser(),
                'password' => 'password',
                'remember' => true,
            ]);
        }
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): Component
    {
        if(app()->environment('local')) {
 
            return Select::make('email')
                // ->label('Author')
                ->options(User::all()->pluck('name', 'email'))
                ->searchable()
                ->label(__('filament-panels::pages/auth/login.form.email.label'))
                // ->email()
                ->required()
                ->default(User::getDefaultUser())
                // ->autocomplete()
                // ->autofocus()
                ->extraInputAttributes(['tabindex' => 1]);

        }
        else {
            return TextInput::make('email')
                ->label(__('filament-panels::pages/auth/login.form.email.label'))
                ->email()
                ->required()
                ->autocomplete()
                ->autofocus()
                ->extraInputAttributes(['tabindex' => 1]);
        }
    
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()"> {{ __(\'filament-panels::pages/auth/login.actions.request_password_reset.label\') }}</x-filament::link>')) : null)
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }
}
