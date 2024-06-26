<?php

namespace io3x1\FilamentTranslations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
//use Filament\Resources\Form;
use Filament\Resources\Resource;
//use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use io3x1\FilamentTranslations\Models\Translation;
use io3x1\FilamentTranslations\Resources\TranslationResource\Pages;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static ?string $slug = 'translations';

    protected static ?string $recordTitleAttribute = 'key';

    public static function getNavigationLabel(): string
    {
        return trans('filament-translations::translation.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('filament-translations.languages-switcher-menu.group', 'Translations');
    }

    public static function getNavigationIcon(): string
    {
        return config('filament-translations.languages-switcher-menu.icon', 'heroicon-o-translate');
    }

    protected function getTitle(): string
    {
        return trans('filament-translations::translation.title.home');
    }

    public static function form(Form $form): Form
    {
        $schema = [];

        foreach (config('filament-translations.locals') as $key => $lang) {
            $schema[] = Forms\Components\Textarea::make('text.'.$key)
                ->label(trans('filament-translations::translation.lang.'.$key))
                ->required();
        }

        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->label(trans('filament-translations::translation.group'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('namespace')
                    ->label(trans('filament-translations::translation.namespace'))
                    ->required()
                    ->default('*')
                    ->maxLength(255),
                Forms\Components\TextInput::make('key')
                    ->label(trans('filament-translations::translation.key'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Builder\Block::make('text')
                    ->label(trans('filament-translations::translation.text'))
                    ->schema($schema),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('group')
                    ->label(trans('filament-translations::translation.group'))
                    ->sortable(),
               TextColumn::make('key')
                    ->label(trans('filament-translations::translation.key'))
                    ->sortable()
                    ->searchable(),
               TextColumn::make('text')
                      ->label(trans('filament-translations::translation.text'))
                      ->searchable(),
               TextColumn::make('created_at')->label(trans('filament-translations::global.created_at'))
                    ->dateTime()->toggleable(),
               TextColumn::make('updated_at')->label(trans('filament-translations::global.updated_at'))
                    ->dateTime()->toggleable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        if (config('filament-translations.modal')) {
            return [
                'index' => Pages\ManageTranslations::route('/'),
            ];
        } else {
            return [
                'index' => Pages\ListTranslations::route('/'),
                'create' => Pages\CreateTranslation::route('/create'),
                'edit' => Pages\EditTranslation::route('/{record}/edit'),
            ];
        }
    }
}
