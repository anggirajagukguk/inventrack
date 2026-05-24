<?php

namespace App\Filament\Resources\Items;

use App\Filament\Resources\Items\Pages\CreateItem;
use App\Filament\Resources\Items\Pages\EditItem;
use App\Filament\Resources\Items\Pages\ListItems;
use App\Models\Item;
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

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nama_barang')
                    ->label('Nama Barang')
                    ->placeholder('Contoh: Laptop Lenovo ThinkPad')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('kode_barang')
                    ->label('Kode Barang')
                    ->placeholder('Contoh: BRG-001')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('stok')
                    ->label('Jumlah Stok')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                Forms\Components\TextInput::make('harga')
                    ->label('Harga Satuan (Rp)')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->prefix('Rp'),

                Forms\Components\Select::make('kondisi')
                    ->label('Kondisi Barang')
                    ->options([
                        'Baik' => 'Baik',
                        'Rusak Ringan' => 'Rusak Ringan',
                        'Rusak Berat' => 'Rusak Berat',
                    ])
                    ->required(),

                Forms\Components\Select::make('lokasi')
                    ->label('Lokasi Penyimpanan')
                    ->options([
                        'Gudang A' => 'Gudang A',
                        'Gudang B' => 'Gudang B',
                        'Gudang C' => 'Gudang C',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Barang')
                    ->placeholder('Jelaskan detail barang ini')
                    ->required()
                    ->rows(3),

                Forms\Components\FileUpload::make('image')
                    ->label('Foto Barang')
                    ->image()
                    ->directory('items')
                    ->visibility('public')
                    ->required(),

                Forms\Components\Hidden::make('users_id'),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('image')
                ->label('Foto')
                ->disk('public'),

            Tables\Columns\TextColumn::make('kode_barang')
                ->label('Kode')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('nama_barang')
                ->label('Nama Barang')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('stok')
                ->label('Stok')
                ->sortable(),

            Tables\Columns\TextColumn::make('harga')
                ->label('Harga')
                ->money('IDR')
                ->sortable(),

            Tables\Columns\TextColumn::make('kondisi')
                ->label('Kondisi')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Baik' => 'success',
                    'Rusak Ringan' => 'warning',
                    'Rusak Berat' => 'danger',
                    default => 'gray',
                }),

            Tables\Columns\TextColumn::make('lokasi')
                ->label('Lokasi')
                ->badge(),

            Tables\Columns\TextColumn::make('user.name')
                ->label('Ditambahkan Oleh'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal')
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
            'index' => ListItems::route('/'),
            'create' => CreateItem::route('/create'),
            'edit' => EditItem::route('/{record}/edit'),
        ];
    }
}
