<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'usuario',
        'email',
        'password',
        'telefono',
        'direccion',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
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
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function getNameAttribute()
    {
        return $this->nombre.' '.$this->apellidos;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('apellidos')->orderBy('nombre');
    }

    public function scopeWhereRole($query, $role)
    {
        switch ($role) {
            case 'Usuario': return $query->where('rol', $role);
            case 'Administrador': return $query->where('rol', $role);
            case 'Colaborador': return $query->where('rol', $role);
            case 'Consultor': return $query->where('rol', $role);
            case 'Editor': return $query->where('rol', $role);
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {                
                $query->where('nombre', 'like', '%'.$search.'%')
                      ->orWhere('apellidos', 'like', '%'.$search.'%')
                      ->orWhere('usuario', 'like', '%'.$search.'%')
                      ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            $trashed === 'only' ? $query->onlyTrashed() : '';
        });
    }
}
