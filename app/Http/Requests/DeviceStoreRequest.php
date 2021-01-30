<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DateTime;
use date;

class DeviceStoreRequest extends FormRequest
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
            'device_token' => ['string','required','unique:devices','starts_with:VT,VE','regex:/^V[T|E][\d]{1,253}$/i','max:255'],
            'admin' => ['string','nullable','max:255'],
            'admin_password' => ['min:8','confirmed','nullable','max:255'],
            'device_name' => ['string','required','unique:devices','max:255'],
            'device_model' => ['string','required','max:255'],
            'notes' => 'nullable',
            'buy_date' => ['date','before_or_equal:'.$todayDate,'required'],
            'serial_no' => ['required','max:255'],
            'warranty_valid_until' => ['date','nullable'],
            'teamviewer_id' => ['string','required','regex:/^\d{1,255}$/i','max:255'],
            'active' => ['boolean','required'],
            'order_no' => 'nullable',
            'product_no' => 'nullable',
            'lease_contract_no' => 'nullable',
            'attachments' => ['nullable','json'],
            'customer_id' => ['required','exists:customers,id'],
            'user_id' => ['required','exists:users,id'],
            'operating_system_id' => ['required','exists:operating_systems,id'],
            'device_type_id' => ['required','exists:device_types,id'],
            'license_name' => ['nullable'|'exists:licenses,name'],
            'software_items' => ['nullable'|'exists:softwares,items'],
            'warranty_description' => ['nullable'|'exists:warranties,description'],
            'manufacturer_id' => ['required','exists:manufacturers,id'],
            'security_software_description' => ['nullable'|'exists:security_softwares,description'],
            'vendor_id' => ['required','exists:vendors,id'],
            'backup_solution_description' => ['nullable'|'exists:backup_solutions,description'],
            'created_at' => ['date_format:"Y-m-d H:i:s/"','before_or_equal:'.$todayformatted],
	        'updated_at' => ['date_format:"Y-m-d H:i:s/"','before_or_equal:'.$todayformatted],
        ];
    }

    /*
    public function foreign_key_rules(){
	return [
            'customer_id' => ['required,exists:customers,id'],
            'user_name' => ['required,exists:users,name'],
            'operating_system_name' => ['required,exists:operating_systems,name'],
            'device_type_type' => ['exists:device_types,type'],
            'license_name' => ['exists:licenses,name'],
            'software_items' => ['nullable|exists:softwares,items'],
            'warranty_description' => ['nullable|exists:warranties,description'],
            'manufacturer_name' => ['required,exists:manufacturers,name'],
            'security_software_description' => ['nullable|exists:security_softwares,description'],
            'vendor_name' => ['required,exists:vendors,name'],
            'backup_solution_description' => ['nullable|exists:backup_solutions,description'],

        ];
    }
    */

    public function messages()
    {
        return [
        'device_token.string' => 'Device token pitää olla merkkijono!',
        'device_token.required' => 'Device token -kenttä on pakollinen!',
	    'device_token.unique' => 'Device token pitää olla uniikki joka laitteelle!',
	    'device_token.starts_with' => 'Device token pitää alkaa merkkisarjalla VE tai VT!',
        'device_token.regex' => 'Device Tokenin pitää alkaa merkeillä VE tai VT ja jatkua numerosarjalla (esim. VT123456)',
        'device_token.max' => 'Device Token saa olla maksimissaan 255 merkkiä pitkä!',
	    'admin.string' => 'Admin: sallitaan vain merkkisarja',
	    'admin.max' => 'Admin: sallitaan vain maksimissaan 255 merkkiä!',
	    'admin_password.max' => 'Admin-salasana: sallitaan vain maksimissaan 255 merkkiä!',
	    'admin_password.min' => 'Admin-salasana: sallitaan minimissään 8 merkkiä!',
	    'admin_password.confirmed' => 'Admin-salasana: vaaditaan salasana vahvistus (tarkista oikeinkirjoitus)!',
        'device_name.string' => 'Laitteen nimi: syötteen pitää olla merkkisarja!',
        'device_name.required' => 'Laitteen nimi -kenttä on pakollinen!',
        'device_name.unique' => 'Laitteen nimi pitää olla uniikki (samannimisiä laitteita ei sallita)!',
        'device_name.max' => 'Laitteen nimi ei saa olla yli 255 merkkiä pitkä!',
	    'device_model.string' => 'Laitten mallin pitää olla merkkijono-muodossa!',
	    'device_model.required' => 'Laitteen malli -kenttä on pakollinen!',
        'device_model.max' => 'Laitteen malli voi olla maksimissaan 255 merkkiä!',
	    'buy_date.date' => 'Ostopäivämäärä kenttä pitää olla muodossa VVVV-MM-DD',
	    'buy_date.required' => 'Ostopäivämäärä on pakollinen!',
	    'buy_date.before_or_equal' => 'Ostopäivämäärä ei voi olla tulevaisuudessa!',
	    'serial_no.required' => 'Sarjanumero on pakollinen!',
	    'serial_no.max' => 'Sarjanumero: sallitaan vain 255 merkkiä!',
	    'warranty_valid_until.date' => 'Takuun päättymispäivä: sallittaan vain VVVV-MM-DD',
	    'teamviewer_id.integer' => 'Teamviewer ID: pitää olla numerosarja!',
        'teamviewer_id.required' => 'Teamviewer ID on pakollinen kenttä!',
	    'teamviewer_id.regex' => 'Teamviewer pitää olla numerosarja!',
	    'teamviewer_id.max' => 'Teamviewer ID voi olla maksimissaan 255 merkkiä pitkä!',
	    'active.boolean' => 'Aktiivinen -kenttä voi olla vain true, false, 1 tai 0!',
	    'active.required' => 'Aktiivinen -kenttä on pakollinen!',
        'attachments.json' => 'Liitteiden tulee olla json-tiedostoja!',
        'customer_id.required' => 'Asiakkaan nimi -kenttä on pakollinen!',
        'customer_id.exists'  => 'Asiakkaan nimi tulee löytyä customers-taulusta!',
        'user_id.required' => 'Käyttäjän nimi -kenttä on pakollinen!',
        'user_id.exists'  => 'Käyttäjän nimi tulee löytyä user-taulusta!',
        'operating_system_name.required' => 'Käyttöjärjestelmän nimi -kenttä on pakollinen!',
        'operating_system_name.exists'  => 'Käyttöjärjestelmän nimi tulee löytyä operating_systems-taulusta!',
        'device_type_type.required' => 'Laitetyyppi -kenttä on pakollinen!',
        'device_type_type.exists'  => 'Laitetyyppi tulee löytyä device_types-taulusta!',
        'license_name.exists'  => 'Lisenssin nimi tulee löytyä licenses-taulusta!',
        'software_items.exists'  => 'Ohjelmisto tulee löytyä softwares-taulusta!',
        'warranty_description.exists'  => 'Takuun kuvaus tulee löytyä warranties-taulusta!',
        'manufacturer_name.required' => 'Valmistajan nimi -kenttä on pakollinen!',
        'manufacturer_name.exists'  => 'Valmistajan nimi tulee löytyä manufacturers-taulusta!',
        'security_software_description.exists'  => 'Tietoturvaohjelmiston kuvaus tulee löytyä security_softwares-taulusta!',
        'vendor_name.required' => 'Myyjän nimi -kenttä on pakollinen!',
        'vendor_name.exists'  => 'Myyjän nimi tulee löytyä vendors-taulusta!',
        'backup_solution_description.exists'  => 'Varmuuskopioratkaisun kuvaus tulee löytyä backup_solutions-taulusta!',
        
        ];
    }
}
