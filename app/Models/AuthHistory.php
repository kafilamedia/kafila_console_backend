<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthHistory extends Model
{
	protected $fillable = ['ip_numbers', 'user_agents', 'login_count', 'last_login_at', 'emergency_key', 'ek_expired_at'];

	protected $casts = [
		'ip_numbers' => 'array',
		'user_agents' => 'array',
	];

	protected $dates = ['last_login_at', 'ek_expired_at'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
