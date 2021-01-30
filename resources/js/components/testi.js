import React from 'react';
import ReactDOM from 'react-dom';

let fields_url = "http://164.90.189.40/harjoitustyo/public/fields?db=";
let resources =    ['addon_devices', 'backup_solutions',  'customers', 'device_types', 'devices', 'licenses', 'manufacturers', 'operating_systems', 'security_softwares', 'softwares', 'user_roles', 'users', 'vendors', 'warranties' ];
let submit_url = "http://164.90.189.40/harjoitustyo/public/create?db=";

class FormApp extends React.Component {
    constructor(props) {
        super(props);
        this.items = props.items;
        this.onFormSubmit = props.onFormSubmit;
    }
    // render this component
    render () {
        let items = this.items.map(function(item, index){
            let neat_arr = item.split("_");
            let neat_str = "";
            if (neat_arr !== item){
                for (let str of neat_arr) {
                    neat_str = neat_str + str.charAt(0).toUpperCase() + str.slice(1);
                }
            }
            else{
                neat_str = item.charAt(0).toUpperCase() + item.slice(1);
            }

            return (<div className="form-group">
                <label htmlFor={item}>{neat_str}</label>
                <input id={item} type="text" className="form-control" name="customer_token" placeholder={neat_str} ref={item} />
            </div>);
        }, this);
        return (
            <>
                {items}
            </>
        );
    }

}

class FormController extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            loaded: false,
            fields: false,
            fetch_url: false,
        };
        this.addItem = this.addItem.bind(this);
        this.showSelectedItem = this.showSelectedItem.bind(this);
        //this.onSubmitHandler = this.onSubmitHandler.bind(this);
    }

    addItem(newItem) {
        let arr = this.state.items;
        arr.push(newItem);
        this.setState({items: arr});
    }

    async showSelectedItem(index) {
	let fetched = "";
        let choice = fields_url + resources[index];
        console.log(choice);
        let fields = await fetch(choice);
	fetched = await fields.json();
	
	console.log(fetched);
	//this.setState();
    }
    async componentDidMount() {
	//reserved
    }

    render() {
        if (this.state.loaded === false) {
            return (<div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <h4>Loading...</h4>
                        <div className="card">
                            <div className="card-header font-weight-bold">Add new items</div>
                            <div className="card-body">

                                <h5>Valitse taulu johon lisätään tietoja</h5>
                                <div className="custom-control custom-radio">
                                    <input type="radio" id="addDeviceRadio" name="addNewItemRadio"
                                           className="custom-control-input" onChange={(e) => this.showSelectedItem(0)}/>
                                    <label className="custom-control-label" htmlFor="addDeviceRadio">Add new addon
                                        device</label>
                                </div>
                                <div className="custom-control custom-radio">
                                    <input type="radio" id="addCustomerRadio" name="addNewItemRadio"
                                           className="custom-control-input" onChange={(e) => this.showSelectedItem(1)}/>
                                    <label className="custom-control-label" htmlFor="addCustomerRadio">Add new backup
                                        solution</label>
                                </div>
                                <div className="custom-control custom-radio">
                                    <input type="radio" id="addUserRadio" name="addNewItemRadio"
                                           className="custom-control-input" onChange={(e) => this.showSelectedItem(2)}/>
                                    <label className="custom-control-label" htmlFor="addUserRadio">Add new
                                        customer</label>
				</div>
                                    <div className="custom-control custom-radio">
                                        <input type="radio" id="addDeviceRadio" name="addNewItemRadio"
                                               className="custom-control-input"
                                               onChange={(e) => this.showSelectedItem(3)}/>
                                        <label className="custom-control-label" htmlFor="addDeviceRadio">Add new device
                                            type</label>
                                    </div>
                                    <div className="custom-control custom-radio">
                                        <input type="radio" id="addCustomerRadio" name="addNewItemRadio"
                                               className="custom-control-input"
                                               onChange={(e) => this.showSelectedItem(4)}/>
                                        <label className="custom-control-label" htmlFor="addCustomerRadio">Add new
                                            device</label>
                                    </div>
                                    <div className="custom-control custom-radio">
                                        <input type="radio" id="addUserRadio" name="addNewItemRadio"
                                               className="custom-control-input"
                                               onChange={(e) => this.showSelectedItem(5)}/>
                                        <label className="custom-control-label" htmlFor="addUserRadio">Add new
                                            license</label>
				    </div>
                                        <div className="custom-control custom-radio">
                                            <input type="radio" id="addDeviceRadio" name="addNewItemRadio"
                                                   className="custom-control-input"
                                                   onChange={(e) => this.showSelectedItem(6)}/>
                                            <label className="custom-control-label" htmlFor="addDeviceRadio">Add new
                                                manufacturer</label>
                                        </div>
                                        <div className="custom-control custom-radio">
                                            <input type="radio" id="addCustomerRadio" name="addNewItemRadio"
                                                   className="custom-control-input"
                                                   onChange={(e) => this.showSelectedItem(7)}/>
                                            <label className="custom-control-label" htmlFor="addCustomerRadio">Add new
                                                operating system</label>
                                        </div>
                                        <div className="custom-control custom-radio">
                                            <input type="radio" id="addUserRadio" name="addNewItemRadio"
                                                   className="custom-control-input"
                                                   onChange={(e) => this.showSelectedItem(8)}/>
                                            <label className="custom-control-label" htmlFor="addUserRadio">Add new
                                                security software</label>
                                        </div>
                                            <div className="custom-control custom-radio">
                                                <input type="radio" id="addDeviceRadio" name="addNewItemRadio"
                                                       className="custom-control-input"
                                                       onChange={(e) => this.showSelectedItem(9)}/>
                                                <label className="custom-control-label" htmlFor="addDeviceRadio">Add new
                                                    software</label>
                                            </div>
                                            <div className="custom-control custom-radio">
                                                <input type="radio" id="addCustomerRadio" name="addNewItemRadio"
                                                       className="custom-control-input"
                                                       onChange={(e) => this.showSelectedItem(10)}/>
                                                <label className="custom-control-label" htmlFor="addCustomerRadio">Add
                                                    new user role</label>
                                            </div>
                                            <div className="custom-control custom-radio">
                                                <input type="radio" id="addUserRadio" name="addNewItemRadio"
                                                       className="custom-control-input"
                                                       onChange={(e) => this.showSelectedItem(11)}/>
                                                <label className="custom-control-label" htmlFor="addUserRadio">Add new
                                                    user</label>
                                            </div>
                                            <div className="custom-control custom-radio">
                                                <input type="radio" id="addCustomerRadio" name="addNewItemRadio"
                                                       className="custom-control-input"
                                                       onChange={(e) => this.showSelectedItem(12)}/>
                                                <label className="custom-control-label" htmlFor="addCustomerRadio">Add
                                                    new vendor</label>
                                            </div>
                                            <div className="custom-control custom-radio">
                                                <input type="radio" id="addUserRadio" name="addNewItemRadio"
                                                       className="custom-control-input"
                                                       onChange={(e) => this.showSelectedItem(13)}/>
                                                <label className="custom-control-label" htmlFor="addUserRadio">Add new
                                                    warranty</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>);
        } else {

        }
        return (
            <>
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-md-8">
                            <div className="card">
                                <div className="card-header font-weight-bold">Add new items</div>
                                <div className="card-body">
                                    <FormApp onFormSubmit={this.addItem} items={this.state.items}/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>

        );
    }
}
export default FormController;

if (document.getElementById('fsgyhnbfvdcx')) {
    ReactDOM.render(<FormController/>, document.getElementById('fsgyhnbfvdcx'));
}

