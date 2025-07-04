<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    //Ultimo Acesso do Usuário
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login' => 'datetime',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function services()
    {
        return $this->hasMany(UserServices::class, 'user_id');
    }

    //Permission and Role Link
    public static function getPermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            ->select('group_name')
            ->groupBy('group_name')->get();
        return $permission_groups;
    }
    // Get Permission
    public static function getpermissionByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();
        return $permissions;
    } // End Method



    //Edit Role Permission
    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $item) {
            if (!$role->hasPermissionTo($item->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
            return $hasPermission;
        }
    }
    //Card
    public function card()
    {
        return $this->hasOne(Card::class);
    }

    // Se o usuário pode ter apenas um contrato aberto
    public function openContract()
    {
        return $this->hasOne(OpenContract::class);
    }

    public function investments()
    {
        return $this->hasMany(UserInvestment::class);
    }

    public function lendings()
    {
        return $this->hasMany(Lending::class);
    }

    //Plan Funcion
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }


    public function balance()
    {
        // Somando todas as transações de entrada com status de sucesso (ex.: crédito, depósito)
        $totalIn = $this->transactions()
            ->where('status', Transaction::STATUS_SUCESSO)
            ->whereIn('operacao', [Transaction::OPERACAO_CREDIT])
            ->sum('amount');
        // Somando todas as transações de saída com status de sucesso (ex.: débito, retirada)
        $totalOut = $this->transactions()
            ->where('status',  Transaction::STATUS_SUCESSO)
            ->whereIn('operacao', [Transaction::OPERACAO_DEBIT])
            ->sum('amount');

        // Retornando o saldo total (entradas - saídas)
        return $totalIn - $totalOut;
    }
}
