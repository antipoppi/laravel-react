<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DateTime;
use date;

class CsvCustomerStoreRequest extends FormRequest
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
            'customer_token' => ['string','required','unique:customers','max:255'],
            'name' => ['string','required','unique:customers','max:255'],
            'address' => ['string','required','max:255'],
            'contact_person_name' => ['string','nullable','max:255'],
            'active' => ['boolean','required'],
            'notes' => ['string','nullable','max:65535'],
            'created_at' => ['date_format:"Y-m-d H:i:s/"','before_or_equal:'.$todayformatted],
	        'updated_at' => ['date_format:"Y-m-d H:i:s/"','before_or_equal:'.$todayformatted],
        ];
    }
    public function messages()
    {
        return [
            'customer_token.required' => 'Customer token -kenttä on pakollinen!',
            'customer_token.string' => 'Customer token pitää olla merkkijono!',
            'customer_token.unique' => 'Customer token pitää olla uniikki joka asiakkaalla!',
            'customer_token.max' => 'Customer token saa olla maksimissaan 255 merkkiä pitkä!',
            'name.required' => 'Asiakkaan nimi -kenttä on pakollinen!',
            'name.string' => 'Asiakkaan nimi pitää olla merkkijono!',
            'name.unique' => 'Asiakkaan nimi pitää olla uniikki joka asiakkaalla!',
            'name.max' => 'Asiakkaan nimi saa olla maksimissaan 255 merkkiä pitkä!',
            'address.required' => 'Asiakkaan osoite -kenttä on pakollinen!',
            'address.string' => 'Asiakkaan osoite pitää olla merkkijono!',
            'address.max' => 'Asiakkaan osoite saa olla maksimissaan 255 merkkiä pitkä!',
            'contact_person_name.string' => 'Yhteishenkilön nimi pitää olla merkkijono!',
            'contact_person_name.max' => 'Yhteishenkilön nimi saa olla maksimissaan 255 merkkiä pitkä!',
            'active.boolean' => 'Aktiivinen -kenttä voi olla vain true, false, 1 tai 0!',
            'active.required' => 'Aktiivinen -kenttä on pakollinen!',
            'notes.string' => 'Muistiinpano pitää olla merkkijono!',
            'notes.max' => 'Muistiinpano saa olla maksimissaan 65535 merkkiä pitkä!',
        ];
    }
}
