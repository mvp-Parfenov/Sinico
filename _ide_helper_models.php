<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Entity{
/**
 * App\Entity\Region
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property Region $parent
 * @property Region[] $children
 */
	class Region extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $verify_token
 * @property string $role
 * @property string $status
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 */
	class User extends \Eloquent {}
}

namespace App\Entity\Adverts{
/**
 * App\Entity\Adverts\Category
 *
 * @property-read \Kalnoy\Nestedset\Collection|\App\Entity\Adverts\Category[] $children
 * @property-read \App\Entity\Adverts\Category $parent
 * @property-write mixed $parent_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Category d()
 */
	class Category extends \Eloquent {}
}

