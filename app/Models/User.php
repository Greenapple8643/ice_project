<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\HasDefaultTenant;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser, HasDefaultTenant, HasTenants
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
        'last_login_ip',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    public static function getDefaultUser(): string {
        $default = User::where('last_login_at', '!=', null)
                    ->orderby('last_login_at','desc')
                    // ->pluck('email')
                    ->first();
        return $default == null ? '' : $default->email;
        // return '';
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function mutateData(array $data, bool $org=FALSE, bool $team=FALSE): array {
        if (Arr::exists($data, 'org_id') or $org){
            $data['org_id'] = $this->current_org;
        }
        
        if (Arr::exists($data, 'team_id') or $team){
            $data['team_id'] = $this->current_team;
        }

        return $data;
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->orgs;
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return Org::find($this->current_org);
    }
 
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->orgs->contains($tenant);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        #return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        return True;
    }

    public function orgs(): BelongsToMany
    {
        return $this->belongsToMany(Org::class);
    }

    public function currentTeam()
    {
        if ($this->current_team ==NULL) {
            return NULL;
        }
        else {
            return Team::find($this->current_team);
        }
    }

    public function currentOrg()
    {
        if ($this->current_org == NULL) {
            return NULL;
        }
        else {
            return Org::find($this->current_org);
        }
    }

}
