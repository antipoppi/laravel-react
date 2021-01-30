import React from 'react';
import ReactDOM from 'react-dom';

let fields_url = "http://164.90.189.40/harjoitustyo/public/fields?db=";
let resources =    ['addon_devices', 'backup_solutions',  'customers', 'device_types', 'devices', 'licenses', 'manufacturers', 'operating_systems', 'security_softwares', 'softwares', 'user_roles', 'users', 'vendors', 'warranties' ];
let submit_url = "http://164.90.189.40/harjoitustyo/public/create?db=";

function ajax_helper(send_url, obj, callback){
	let send_data = {};
	//console.log(obj);
	for (let x in obj) {
		send_data[[obj[x].key]] = obj[x].value;
	}
	//console.log('send_data');
	//console.log(send_data);
	$.ajax({
		url: send_url,
		type:'GET',
		headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		data: send_data,
		dataType: 'json',
		success: function(response) {
			console.log('succesful ajax');
			//console.log(response);
			callback({'type': 'success', 'message': response});

		    Array.from(document.querySelectorAll("input")).forEach(
		        input => (input.value = "")
		    );
		    Array.from(document.querySelectorAll('textarea')).forEach(
		        textarea => (textarea.value = "")
		    );
		    Array.from(document.querySelectorAll('select')).forEach(
		        select => (select.value = "")
		    );
		},
		error: function(xhr) {
			console.log('xhr');
		    console.log(xhr);
		    //console.log(JSON.stringify(xhr.responseJSON.errors));
		    var errors = xhr.responseJSON.message;
			console.log('errors');
		    console.log(errors);
		    callback({type: 'error', message: errors});
			return false;
		}
	});
}

class FormControllerDev extends React.Component {

    constructor(props) {
        super(props);
		let defaults = (this.props.defaults) ? this.props.defaults : null;
        this.state = {
            select_data: false,
            fetch_url: false,
	    	save_url : false,
			loaded: false,
			items: null,
			foreign_keys: null,
			submit_data: null,
			status: null,
			defaults: defaults
        };
        this.handleChange = this.handleChange.bind(this);
        this.showSelectedItem = this.showSelectedItem.bind(this);
		this.foreign_keys = {};
		this.getSelectFields = this.getSelectFields.bind(this);
		this.getForeignKeys = this.getForeignKeys.bind(this);
		this.dbResolver = this.dbResolver.bind(this);
        this.onSubmitHandler = this.onSubmitHandler.bind(this);
		this.getRadioButtons = this.getRadioButtons.bind(this);
		this.submitStatusHandler = this.submitStatusHandler.bind(this);
		this.getStatus = this.getStatus.bind(this);
    }

	async submitStatusHandler(msg){
		console.log('msg submitstatushandler');
		console.log(msg);
		console.log((msg.type === "error"));
		console.log(('msg.type === error'));
		let statusmsg = msg.message;
		if (msg.type === "error") {
		    this.setState({status: {type: 'error', message: statusmsg}});
		} else if (msg.type === 'success') {
		    this.setState({submit_data: null, status: {type: 'success', message: statusmsg}});
		}


	}

	handleChange(e, item){
		let vals =	[];
		if (this.state.submit_data){
			vals = this.state.submit_data;
		}
		if (vals.length && vals[vals.length - 1].key === e.target.id) {
			vals.pop();
		}
		vals.push({'key': e.target.id, 'value': e.target.value});
	
		this.setState({submit_data: vals});
	}

    onSubmitHandler(event) {
		event.preventDefault();
		if (!this.state.submit_data){
			this.setState({status: {type: 'error', message: "Ei tallennettavia tietoja, kirjoita jotain!"}});
			return false;
		}
		ajax_helper(this.state.save_url, this.state.submit_data, this.submitStatusHandler);
        
    }
	
	getForeignKeys(fields){
		// this.foreign_keys on dirty hack, jonka avulla saamme array foreachiltä foreign keyt
		// TO-DO: paranna tätä funktioita ja dumppaa this.foreign_keys
		this.foreign_keys = {};
		fields.map(function(item, index){
				Object.entries(item).forEach(([key,value])=>{
					if ((value === "id" || value === "created_at" || value === "updated_at") && key === "Field") {
						
						}
				   	if (value === "MUL" && key == "Key" ){
						let new_db = this.dbResolver(item['Field']); 
						//console.log('foreign key!');
						let foreign_key = item['Field'];
						this.foreign_keys[foreign_key] = new_db;
					}
				});

			}, this);
		let foreign_keys = this.foreign_keys;
		this.foreign_keys = null;
		return foreign_keys;
	}
	
		dbResolver(input){
		// otetaan poikkeukset huomioon jos foreign keyn nimi ei vastaa yleistä sääntöä
		let return_name = "";	
		input === 'main_device_id' ? return_name = 'devices' : input === 'warranty_id' || input === 'warranty_type_id' ? return_name = 'warranties' : input === 'device_type_id' ? return_name = 'device_types' : input === 'operating_system_id' ? return_name = 'operating_systems' : input === 'security_software_id' ? return_name = 'security_softwares' : return_name = input;
		// ja seuraavaksi yleiset tapaukset (esim. vendor_id = vendors)
		if (return_name === input){
			let arr = input.split("_");
			arr.pop();
			return_name = arr.join("_") + "s"
		}
		
		return return_name;
	}
	
	async getSelectFields(keys){
		let url = "http://164.90.189.40/harjoitustyo/public/fetch?db=";
		let select_fields  = await Object.entries(keys).map(async function(item, key){
			//console.log('item[1]');
			//console.log(item[1]);
			//console.log('item');
			//console.log(item);
			let db_name = item[1];
			//console.log('db_name');
			//console.log(db_name);
			let select_url = url + db_name;
			//console.log('select_url');
			//console.log(select_url);
			let response = await fetch(select_url);
			let fetch_data = await response.json();
			//console.log('fetch_data');
			//console.log(fetch_data);
			let select_key = item[0];
			return {"key":select_key, "data": fetch_data};
			
  		}, this);
		return await Promise.all(select_fields);
	}

    async showSelectedItem(e, index) {
		//console.log(e.target.checked);
	    let url_choice = fields_url + resources[index];
		let db = resources[index];
		//console.log('url_choice');
	    //console.log(url_choice);
	    let response = await fetch(url_choice);
		let response_data = await response.json();
		let save_to = submit_url + resources[index];
		let items = response_data;
		let saveurl = save_to;
		let fetch_url = url_choice;
		// entine form-app alkaa
        let foreign_keys = this.getForeignKeys(response_data);
		let select_fields = {};
		if (foreign_keys){
			select_fields = await this.getSelectFields(foreign_keys);	
		}
		// entinen form-app loppuu

		this.setState({submit_data: null, status: null, foreign_keys: foreign_keys, select_data: select_fields, loaded: true, items:response_data, db:db, fetch_url: url_choice, save_url: save_to});
	}

	getStatus(){
		if (this.state.status){
		    if (this.state.status.type === "success"){
		        let msg = this.state.status.message;
		        if (this.state.status.type === "success"){
					console.log('this.state.status');
					console.log(this.state.status);
		            let new_user = Object.keys(msg).map(function(key){
		                let str = [key][0];
		                //console.log(str);
		                let neat_str = str.charAt(0).toUpperCase() + str.slice(1) + " ";
		                return (<><span>{neat_str}: {msg[key]}</span><br/></>);
		            });
		            return (<>
		                <div className="alert alert-success" role="alert"><h5 className="alert-heading">Tiedot tallennettu onnistuneesti!</h5> Uuden tietueen tiedot:<hr/>
		                    {new_user}
		                </div>
		            </>);
		        }
		    }
		    console.log('errmsg');
		    console.log(this.state.status.message);
			return (<>
		                <div className="alert alert-danger" role="alert"><h5 className="alert-heading">Tallennus epäonnistui</h5> Virheen tiedot:<hr/>
		                    {this.state.status.message}
		                </div>
		            </>);
		}
		return (<>
		    <div className="alert alert-info" role="alert"> Luo uusi tietua painamalla submit-nappia.
		    </div>
		</>);
	}
		
 
    async componentDidMount() {
	
	}
	
	getRadioButtons(){
		let radiobuttons = resources.map(function(item, index){
				let input_id = "add" + item;
				let neat_arr = item.split("_");
				let neat_str = "";
				if (neat_arr !== item){
				    for (let str of neat_arr) {
				        neat_str = neat_str + str.charAt(0).toUpperCase() + str.slice(1) + " ";
				    }
				}
				else{
				    neat_str = item.charAt(0).toUpperCase() + item.slice(1);
				}
				let label_text = "Add new " + neat_str;
		        return (<div className="custom-control custom-radio">
		                            <input type="radio" id={input_id} name="AddItemButton"
		                                   className="custom-control-input" onChange={(e) => this.showSelectedItem(e, index)} />
		                            <label className="custom-control-label" htmlFor={input_id}>{label_text}</label>
		                        </div>);
		    }, this);
		return radiobuttons;
	}
	
	genForm(){
		let form = this.state.items.map(function (item, index) {
		if (item.Field === "id" || item.Field === "created_at" || item.Field === "updated_at") {
		    return false;
		}
		let select_fields = null;
		let neat_arr = item.Field.split("_");
		let neat_str = "";
		if (neat_arr !== item) {
		    for (let str of neat_arr) {
		        neat_str = neat_str + str.charAt(0).toUpperCase() + str.slice(1) + " ";
		    }
		} else {
		    neat_str = item.charAt(0).toUpperCase() + item.slice(1);
		}
		let header_fields = item.Null === "NO" ? "required" : "";
		let placeholder = item.Null === "NO" ? "pakollinen" : "vaihtoehtoinen";
		placeholder = neat_str + placeholder + item.Key === "UNI" ? ", uniikki" : "";
		let input_type = item.Type !== "date" ? "text" : "date";
		//
		this.item = item;
		let is_select  = Object.keys(this.state.foreign_keys);
		let select_obj =	null;
		if (is_select.includes(this.item.Field)){
		    select_obj = this.state.select_data.find(obj => obj.key === item.Field);
		}
		if (select_obj){
			//console.log('select_obj');
			//console.log(select_obj);
			let select_fields = select_obj.data.map(function (item, index) {
				//console.log('item');
				//console.log(item);
				let entries = Object.entries(item);

				// etsitään select kentälle nimi, joka näkyy valintavalikossa, otetaan ensimmäinen arvo...
				// joka ei ole numero		
				let val = null;
					for (let x in entries) {
					if (isNaN(parseFloat(entries[x][1])) && !isFinite(entries[x][1])){
					    val = entries[x][1];
						break;
					}
				}
		    	return (<option value={item.id}>{val}</option>);
			}, this);
		let default_option = item.Null === "NO" ? "Ei valintaa (pakollinen kenttä)" : " Ei valintaa";
		console.log('item');
		console.log(item);
		return (<div className="form-group">
		    <label htmlFor={item.Field}>{neat_str}</label>
		    <select id={item.Field} type={input_type} className="custom-select" name={item.Field}
		            placeholder={placeholder} ref={item.Field} onChange={(e) => this.handleChange(e)} >
				<option value='null'>{default_option}</option>
		        {select_fields}
		    </select></div>);
	}
		return (<div className="form-group">
		    <label htmlFor={item.Field}>{neat_str}</label>
		    <input id={item.Field} type={input_type} className="form-control" name={item.Field}
		           placeholder={placeholder} ref={item.Field} onChange={(e) => this.handleChange(e)} />
		</div>);

		}, this);
		return form;
	}

    render() {
		console.log(this.state);

		let radiobuttons = this.getRadioButtons();
	
        if (this.state.loaded === false) {
			
            return (
				<>
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-md-8">
                                <div className="card">
                                    <div className="card-header font-weight-bold">Add new items</div>
                                    <div className="card-body">
                                        <h5>Valitse taulu johon lisätään tietoja</h5>
										<br/>
                                        {radiobuttons}
										<br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </>);
        }

		let form = this.genForm();
		let neat_db_arr = this.state.db.split("_");
        let neat_db_name = "";
        if (neat_db_arr !== this.state.db) {
            for (let str of neat_db_arr) {
                neat_db_name = neat_db_name + str.charAt(0).toUpperCase() + str.slice(1) + " ";
            }
        }
		
		let status = this.getStatus();
		
        return (
            <>
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-md-8">
                            <div className="card">
                                <div className="card-header font-weight-bold">Add new items</div>
                                <div className="card-body">
								<h5>Valitse taulu johon lisätään tietoja</h5>
								<br/>
								{radiobuttons}
								<br/>
								<h5>{neat_db_name} -taulun lisäyslomake</h5>
								<form className="d-block">
		                			{form}
									<button className="btn btn-dark" type="submit" onClick={this.onSubmitHandler}>Submit</button>
								</form>
								<br/>
								{status}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>

        );
    }
}
export default FormControllerDev;

if (document.getElementById('fsgyhnbfvdcx_ex')) {
    ReactDOM.render(<FormControllerDev/>, document.getElementById('fsgyhnbfvdcx_ex'));
}

