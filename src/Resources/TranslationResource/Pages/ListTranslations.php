<?php

namespace io3x1\FilamentTranslations\Resources\TranslationResource\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
//use Filament\Pages\Actions\Action;
//use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use io3x1\FilamentTranslations\Services\SaveScan;
use io3x1\FilamentTranslations\Resources\TranslationResource;

class ListTranslations extends ListRecords
{

    protected static string $resource = TranslationResource::class;


    public function getTitle(): string
    {
        return trans('filament-translations::translation.title.list');
    }

    protected function getActions(): array
    {
        return [
            Action::make('scan')->action('scan')->label(trans('translation.scan')),
            Action::make('settings')
                ->label('Settings')
                ->icon('heroicon-o-cog')
                ->form([
                    Select::make('language')
                        ->label('Language')
                        ->default(auth()->user()->lang)
                        ->options(config('filament-translations.locals'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $user = User::find(auth()->user()->id);

                    $user->lang = $data['language'];
                    $user->save();

                    session()->flash('notification', [
                        'message' => __(trans('translation.notification') . $user->lang),
                        'status' => "success",
                    ]);

                    redirect()->to('admin/translations');
                }),
        ];
    }

    /**
     * @return void
     */
    public function scan(): void
    {
        $scan = new SaveScan();
        $scan->save();

        $this->notify('success', 'Translation Has Been Loaded');
    }
}
