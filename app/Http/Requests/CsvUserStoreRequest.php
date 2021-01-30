<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DateTime;
use date;

class CsvUserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $todayDate = date('Y-m-d');
        $todayDatetime = new DateTime('now');
        $todayformatted= $todayDatetime->format('Y-m-d H:i:s');
        return [
            'name' => ['string','required','max:255'],
            'email' => ['email:rfc,dns','required','unique:users','max:255'],
            'user_role_id' => ['required','exists:users,id'],
            'password' => ['min:8','string','required','confirmed','max:255'],
            'notes' => ['string','nullable','max:65535'],
            'tel' => ['string','regex:/^[+?]{0,1}[\d ]{0,20}$/i','nullable','max:255'],
            'active' => ['boolean','required'],
            'remember_token' => ['string','nullable','max:100'],
            'created_at' => ['date_format:"Y-m-d H:i:s/"','before_or_equal:'.$todayformatted],
	        'updated_at' => ['date_format:"Y-m-d H:i:s/"','before_or_equal:'.$todayformatted],

        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Käyttäjän nimi -kenttä on pakollinen!',
            'name.string' => 'Käyttäjän nimi pitää olla merkkijono!',
            'name.max' => 'Käyttäjän nimi saa olla maksimissaan 255 merkkiä pitkä!',
            'email.required' => 'Sähköpostiosoite -kenttä on pakollinen!',
            'email.email' => 'Sähköpostiosoite väärässä muodossa!',
            'email.unique' => 'Sähköpostiosoitteen pitää olla uniikki users-taulussa!',
            'email.max' => 'Sähköpostiosoite saa olla maksimissaan 255 merkkiä pitkä!',
            'user_role_id.required' => 'Käyttäjän rooli -kenttä on pakollinen!',
            'user_role_id.exists'  => 'Käyttäjän rooli tulee löytyä user_roles-taulusta!',
            'password.required' => 'Salasana -kenttä on pakollinen!',
            'password.string' => 'Salasana pitää olla merkkijono!',
            'password.max' => 'Salasana saa olla maksimissaan 255 merkkiä pitkä!',
            'password.min' => 'Salasana: sallitaan minimissään 8 merkkiä!',
            'password.confirmed' => 'Salasana: vaaditaan salasana vahvistus (tarkista oikeinkirjoitus)!',
            'notes.string' => 'Muistiinpano pitää olla merkkijono!',
            'notes.max' => 'Muistiinpano saa olla maksimissaan 65535 merkkiä pitkä!',
            'tel.string' => 'Puhelinnumero pitää olla merkkijono!',
            'tel.max' => 'Puhelinnumero saa olla maksimissaan 255 merkkiä pitkä!',
            'active.boolean' => 'Aktiivinen -kenttä voi olla vain true, false, 1 tai 0!',
            'active.required' => 'Aktiivinen -kenttä on pakollinen!',
            'remember_token.string' => 'Remember token pitää olla merkkijono!',
            'remember_token.max' => 'Remember token saa olla maksimissaan 255 merkkiä pitkä!',
        ];
    }
}
