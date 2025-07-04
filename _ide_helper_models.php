<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Subconta
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $cpfCnpj
 * @property string $birthDate
 * @property string|null $companyType
 * @property string $phone
 * @property string $mobilePhone
 * @property string $address
 * @property string $addressNumber
 * @property string|null $complement
 * @property string $province
 * @property string $postalCode
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereAddressNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereCompanyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereComplement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereCpfCnpj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereMobilePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subconta whereUpdatedAt($value)
 */
	class Subconta extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $type
 * @property string $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $receiver
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $avatar
 * @property string $email
 * @property string|null $cpfCnpj
 * @property string $role
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $bank
 * @property string $agency
 * @property string $account
 * @property string|null $pix_key
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $balance
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAgency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCpfCnpj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePixKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

