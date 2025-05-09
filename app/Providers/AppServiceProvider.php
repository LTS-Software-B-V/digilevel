<?php
namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentSettingsHub\Facades\FilamentSettingsHub;
use TomatoPHP\FilamentSettingsHub\Services\Contracts\SettingHold;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
        //     $event->extendSocialite('azure', \SocialiteProviders\Azure\Provider::class);
        // });
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['nl', 'en'])

                ->flags([
                    'en' => asset('/images/flags/en.svg'),
                    'nl' => asset('/images/flags/nl.svg'),
                ])->circular()

                ->visible(outsidePanels: false);
            //->outsidePanelPlacement(Placement::BottomRight);
        });

        FilamentSettingsHub::register([
            SettingHold::make()
                ->order(2)
                ->label('Site Settings') // to translate label just use direct translation path like `messages.text.name`
                ->icon('heroicon-o-globe-alt')
                ->route('filament.admin.pages.site-settings')                    // use page / route
                ->page(\TomatoPHP\FilamentSettingsHub\Pages\SiteSettings::class) // use page / route
                ->description('Name, Logo, Site Profile')                        // to translate label just use direct translation path like `messages.text.name`
                ->group('General'),                                              // to translate label just use direct translation path like `messages.text.name`,
        ]);

        Gate::define('viewApiDocs', function (User $user) {
            return in_array($user->email, ['superadmin@ltssoftware.nl']);
        });

        Filament::registerNavigationGroups([
            'Objecten',
            'Blog',
            'Settings',
        ]);

        FilamentAsset::register([
            Css::make('custom-stylesheet', __DIR__ . '/../../resources/css/tenant.css'),
        ]);

        FilamentColor::register([
            'primary' => Color::hex('#ff0000'),
        ]);
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                // UserMenuItem::make()
                //     ->label('Instellingen')
                //     ->url(route('filament.admin.general'))
                //     ->icon('heroicon-s-cog'),
                // UserMenuItem::make()
                //     ->label('Logboek')
                //     ->url(route('filament.admin.logs'))
                //     ->icon('heroicon-m-clipboard-document-list'),
                UserMenuItem::make()
                    ->label('Mijn profiel')
                    ->url('/admin/edit-profile')
                    ->icon('heroicon-o-user'),

            ]);
        });

        Model::unguard();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
