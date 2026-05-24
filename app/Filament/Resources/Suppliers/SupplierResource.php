<?php

namespace App\Filament\Resources\Suppliers;

use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Suppliers\Pages\EditSupplier;
use App\Filament\Resources\Suppliers\Pages\ListSuppliers;
use App\Models\Supplier;
use Filament\Actions\EditAction as TableEditAction;
use Filament\Actions\DeleteAction as TableDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->placeholder('Contoh: PT. Sumber Makmur')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('nama_kontak')
                    ->label('Nama Contact Person')
                    ->placeholder('Contoh: Budi Santoso')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('telepon')
                    ->label('Nomor Telepon')
                    ->placeholder('Contoh: 08123456789')
                    ->required()
                    ->maxLength(15),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->placeholder('Contoh: supplier@email.com')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat Lengkap')
                    ->placeholder('Jl. Contoh No. 123, Kota, Provinsi')
                    ->required()
                    ->rows(3),

                Forms\Components\FileUpload::make('image')
                    ->label('Logo Perusahaan')
                    ->image()
                    ->directory('suppliers')
                    ->visibility('public')
                    ->required(),
            ]);
    }
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('image')
                ->label('Logo')
                ->disk('public'),

            Tables\Columns\TextColumn::make('nama_perusahaan')
                ->label('Perusahaan')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('nama_kontak')
                ->label('Contact Person')
                ->searchable(),

            Tables\Columns\TextColumn::make('telepon')
                ->label('Telepon'),

            Tables\Columns\TextColumn::make('email')
                ->label('Email'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Ditambahkan')
                ->dateTime('d M Y')
                ->sortable(),
        ])
        ->filters([
            //
        ])
        ->actions([
            TableEditAction::make(),
            TableDeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuppliers::route('/'),
            'create' => CreateSupplier::route('/create'),
            'edit' => EditSupplier::route('/{record}/edit'),
        ];
    }
}
