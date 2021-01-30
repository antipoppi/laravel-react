import React from 'react';
import ReactDOM from 'react-dom';

let fields_url = "http://164.90.189.40/harjoitustyo/public/fields?db=";
let resources =    ['addon_devices', 'backup_solutions',  'customers', 'device_types', 'devices', 'licenses', 'manufacturers', 'operating_systems', 'security_softwares', 'softwares', 'user_roles', 'users', 'vendors', 'warranties' ];
let submit_url = "http://164.90.189.40/harjoitustyo/public/create?db=";

class FormApp extends React.Component {
    constructor(props) {
        super(props);
        this.items = this.props.items;
	this.foreign_keys = {};
	this.childKey = 0;
	let values = (this.props.values) ? this.props.values : null;
    this.onFormSubmit = this.props.onFormSubmit;
	this.db = this.props.db;
	/* state-muuttujat: 
	*	loaded: boolean, joka kertoo onko kenttien sisältö latautunut
	*	data: lomakkeen kenttien tiedot, tämä ladataan vasta ensimmäisen renderöinnin jälkeen
	*   items: pakollinen kenttä, saadaan propsista. Sisältää lomakkeen kenttien kaikki ominaisuudet
	* 	values: sisältää lomakkeen kenttien oletusarvot, voi olla myös null. Saadaan propseista
	* 	foreign_keys: sisältää kaikkien kentätä, jotka ovat riippuvaisia muista tauluista	
	*/
	this.state = {loaded: false, data: null, values: values, items: this.props.items, foreign_keys: null};
    }

    async componentDidMount() {
		let select_options = {};
		let url = "http://164.90.189.40/harjoitustyo/public/fetch?db=";
        let items = this.items.map(function(item, index){
			Object.entries(item).forEach(([key,value])=>{
				if ((value === "id" || value === "created_at" || value === "updated_at") && key === "Field") {
					
					}
				   	if (value === "MUL" && key == "Key" ){
						// otetaan poikkeukset huomioon jos foreign keyn nimi ei vastaa yleistä sääntöä
						let str = item['Field'] === 'main_device_id' ? 'devices' : item['Field'] === 'warranty_type_id' ? 'warranties' : item['Field'] === 'device_type_id' ? 'device_types' : item['Field'] === 'operating_system_id' ? 'operating_systems' : item['Field'] === 'security_software_id' ? 'security_softwares' : '';
						let new_db = str === "" ? item['Field'].split("_")[0] + "s": str;
						console.log('foreign key!');
						let foreign_key = item['Field'];
						this.foreign_keys[foreign_key] = new_db;
					}
			});

		}, this);	
		let select_fulfilled = null;
		if (this.foreign_keys.length){
			console.log(this.foreign_keys);
	 		select_options  = await Object.entries(this.foreign_keys).map(async function(item, key){
				let select_url = url + item[1]	;
				let response = await fetch(select_url);
				let fetch_data = await response.json();
				console.log(fetch_data);
				let select_key = item[0];
				return {"key": select_key, "data": fetch_data};
				
	  		});
		select_fulfilled = await Promise.all(select_options);
		}
		// yes
		this.setState({'foreign_keys': this.foreign_keys, 'data': select_fulfilled, 'loaded': true});
    }
    render () {
	if (this.state.loaded === true){
		console.log(this.state);

		let items = this.items.map(function(item, index){
		    if (item.Field === "id" || item.Field === "created_at" || item.Field === "updated_at") {
			return false;
		    }
		    let neat_arr = item.Field.split("_");
		    let neat_str = "";
		    if (neat_arr !== item){
		        for (let str of neat_arr) {
		            neat_str = neat_str + str.charAt(0).toUpperCase() + str.slice(1) + " ";
		        }
		    }
		    else{
		        neat_str = item.charAt(0).toUpperCase() + item.slice(1);
		    }
		    let header_fields = item.Null === "NO" ? "required" : "";
		    let placeholder = item.Null === "NO" ? "pakollinen" : "vaihtoehtoinen";
		    placeholder = neat_str + placeholder + item.Key === "UNI" ? ", uniikki" : "";
		    let input_type = item.Type !== "date" ? "text" : "date";
		    return (<div className="form-group" >
		        <label htmlFor={item.Field}>{neat_str}</label>
		        <input id={item.Field} type={input_type} className="form-control" name={item.Field} placeholder={placeholder} ref={item.Field} />
		    </div>);
		}, this);
		return (
		    <>
				<h5>{this.db} -taulun lisäyslomake</h5>
		        {items}
		    </>
		);
    	}
	return (
           	<h4>Loading...</h4>
		);
    }

}

class FormControllerDev extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            data: false,
            fetch_url: false,
			items: {},
	    	save_url : false
        };
		this.button_ids= new Array();
		this.db = null;
		this.loaded = false;
        this.addItem = this.addItem.bind(this);
        this.showSelectedItem = this.showSelectedItem.bind(this);
        //this.onSubmitHandler = this.onSubmitHandler.bind(this);
		this.loaded = false;
    }

    addItem(newItem) {
		e.preventDefault();
        let arr = this.state.items;
        arr.push(newItem);
        this.setState({items: arr});
    }

    async showSelectedItem(e, index) {
		//console.log(e.target.checked);
		if (e.target.checked){
		    let url_choice = fields_url + resources[index];
			this.db = resources[index];
		    //console.log(url_choice);
		    let response = await fetch(url_choice);
			let response_data = await response.json();
			let save_to = submit_url + resources[index];
			//console.log('response_data');
			//console.log(response_data);
			this.items = response_data;
			this.save_url = save_to;
			this.fetch_url = url_choice;
			this.loaded = true;
			this.setState({loaded:true, fetch_url: url_choice, save_url: url_choice});
			}
		
    }
    componentDidMount() {	
    }

    render() {
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
				this.button_ids.push(input_id);
				let label_text = "Add new " + neat_str;
		        return (<div className="custom-control custom-radio">
		                            <input type="radio" id={input_id} name="AddItemButton"
		                                   className="custom-control-input" onChange={(e) => this.showSelectedItem(e, index)}/>
		                            <label className="custom-control-label" htmlFor={input_id}>{label_text}</label>
		                        </div>);
		    }, this);
        if (this.loaded === false) {
			
            return (
				<>
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-md-8">
                                <div className="card">
                                    <div className="card-header font-weight-bold">Add new items</div>
                                    <div className="card-body">
                                        <h5>Valitse taulu johon lisätään tietoja</h5>
                                        {radiobuttons}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </>);
        }
		this.childKey = this.childKey + 1;
		this.loaded = false;
        return (
            <>
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-md-8">
                            <div className="card">
                                <div className="card-header font-weight-bold">Add new items</div>
                                <div className="card-body">
								<h5>Valitse taulu johon lisätään tietoja</h5>
								{radiobuttons}
                                    <FormApp key={this.childKey} onFormSubmit={this.addItem} items={this.state.items} db={this.db}/>
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

if (document.getElementById('fsgyhnbfvdcx_dev')) {
    ReactDOM.render(<FormControllerDev/>, document.getElementById('fsgyhnbfvdcx_dev'));
}

