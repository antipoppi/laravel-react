import React from 'react';
import ReactDOM from 'react-dom';
//lisätään nappi joka piilottaa select valikon ja 
class Addnewitem extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            loaded: false,
            status: 0, //0 show nothing, 1 show device, 2 show customer, 3 show user
            showform_1: false,
            showform_2: false,
            showform_3: false,

            user_id_data: null,
            user_id_selected: "",
            customer_token_data: null,
            customer_token_selected: "",
            admin: "",
            admin_password: "",
            admin_password_confirmation: "",
            operating_system_id_data: null,
            operating_system_id_selected: "",
            device_model: "",
            device_type_id_data: null,
            device_type_id_selected: "",
            device_name: "",
            license_id_data: null,
            license_id_selected: "",
            software_id_data: null,
            software_id_selected: "",
            buy_date: "",
            serial_no: "",
            warranty_id_data: null,
            warranty_id_selected: "",
            warranty_valid_until: "",
            teamviewer_id: "",
            security_software_id_data: null,
            security_software_id_selected: "",
            order_no: "",
            vendor_id_data: null,
            vendor_id_selected: "",
            product_no: "",
            manufacturer_id_data: null,
            manufacturer_id_selected: "",
            backup_solution_id_data: null,
            backup_solution_id_selected: "",
            lease_contract_no: "",

            customer_token: "",
            name: "",
            address: "",
            contact_person_name: "",
            active: 1,
            notes: "",

            user_role_id_data: null,
            user_role_id_selected: "",
            tel: "",
            email: "",
            password: "",
            password_confirmation: "",
            response: ""
        };
        this.onChangeHandler = this.onChangeHandler.bind(this);
        this.showSelectedItem = this.showSelectedItem.bind(this);
        this.onSubmitHandler = this.onSubmitHandler.bind(this);
    }

    async componentDidMount() {
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=user_roles")
        .then(response => response.json())
        .then(data => {
            this.setState({user_role_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=customers")
        .then(response => response.json())
        .then(data => {
            this.setState({customer_token_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=users")
        .then(response => response.json())
        .then(data => {
            this.setState({user_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=operating_systems")
        .then(response => response.json())
        .then(data => {
            this.setState({operating_system_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=device_types")
        .then(response => response.json())
        .then(data => {
            this.setState({device_type_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=licenses")
        .then(response => response.json())
        .then(data => {
            this.setState({license_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=softwares")
        .then(response => response.json())
        .then(data => {
            this.setState({software_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=warranties")
        .then(response => response.json())
        .then(data => {
            this.setState({warranty_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=security_softwares")
        .then(response => response.json())
        .then(data => {
            this.setState({security_software_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=vendors")
        .then(response => response.json())
        .then(data => {
            this.setState({vendor_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=manufacturers")
        .then(response => response.json())
        .then(data => {
            this.setState({manufacturer_id_data: data});
        })
        await fetch("http://164.90.189.40/harjoitustyo/public/fetch?db=backup_solutions")
        .then(response => response.json())
        .then(data => {
            this.setState({backup_solution_id_data: data});
        })
        this.setState({loaded: true});
    }

    onChangeHandler = (event) => {
        //console.log(event.target.name);
        //console.log(event.target.value);
        let nam = event.target.name;
        let val = event.target.value;
        if((event.target.checked == true) && (nam == "active")){
            val = 1; 
        }
        if((event.target.checked != true) && (nam == "active")){
            val = 0; 
        }
        this.setState({[nam]: val});
    }

    showSelectedItem = (status) => {
        this.setState({status});
        if(status == 1){
            this.setState({showform_1: true, showform_2: false, showform_3:false, response:""});
        }
        if(status == 2){
            this.setState({showform_1: false, showform_2: true, showform_3: false, response:""});
        }
        if(status == 3){
            this.setState({showform_1: false, showform_2: false, showform_3: true, response:""});
        }
    }

    onSubmitHandler(event) {
        event.preventDefault();
        if (this.state.status == 1){
            var data2 = {db:"devices",device_token:this.state.device_token,customer_id:this.state.customer_token_selected,admin:this.state.admin,admin_password:this.state.admin_password,admin_password_confirmation:this.state.admin_password_confirmation,user_id:this.state.user_id_selected,operating_system_id:this.state.operating_system_id_selected,device_model:this.state.device_model,device_type_id:this.state.device_type_id_selected,device_name:this.state.device_name,notes:this.state.notes,license_id:this.state.license_id_selected,software_id:this.state.software_id_selected,buy_date:this.state.buy_date,serial_no:this.state.serial_no,warranty_id:this.state.warranty_id_selected,warranty_valid_until:this.state.warranty_valid_until,teamviewer_id:this.state.teamviewer_id,security_software_id:this.state.security_software_id_selected,active:this.state.active,order_no:this.state.order_no,vendor_id:this.state.vendor_id_selected,product_no:this.state.product_no,manufacturer_id:this.state.manufacturer_id_selected,backup_solution_id:this.state.backup_solution_id_selected,lease_contract_no:this.state.lease_contract_no};
            var newurl = "http://164.90.189.40/harjoitustyo/public/createdevice";
        }
        if (this.state.status == 2){
            var data2 = {db:"customers",customer_token:this.state.customer_token,name:this.state.name,address:this.state.address,contact_person_name:this.state.contact_person_name,active:this.state.active,notes:this.state.notes};
            var newurl = "http://164.90.189.40/harjoitustyo/public/createcustomer";
        }
        if (this.state.status == 3){
            var data2 = {db:"users",user_role_id:this.state.user_role_id_selected,customer_id:this.state.customer_token_selected,name:this.state.name,email:this.state.email,password:this.state.password,password_confirmation:this.state.password_confirmation,notes:this.state.notes,tel:this.state.tel,active:this.state.active};
            var newurl = "http://164.90.189.40/harjoitustyo/public/createuser";
        }
        
        var me = this;
        $.ajax({
            url: newurl,
            type:'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data2,
            dataType: 'json',
            success: function(response) {
                console.log(newurl);
                //var responseString = JSON.parse(response);
                me.setState({response: <div className="alert alert-success" role="alert">Tiedot tallennettu onnistuneesti!</div>});
                console.log(response);
                
                Array.from(document.querySelectorAll("input")).forEach(
                    input => (input.value = "")
                );
                Array.from(document.querySelectorAll('textarea')).forEach(
                    textarea => (textarea.value = "")
                );
                Array.from(document.querySelectorAll('select')).forEach(
                    select => (select.value = "")
                );

                if (me.state.status == 1){
                    me.setState({
                        user_id_selected: "Select user from this select menu, pakollinen",
                        customer_token_selected: "Select customer token from this select menu, pakollinen",
                        admin: "",
                        admin_password: "",
                        admin_password_confirmation: "",
                        operating_system_id_selected: "Select operating system from this select menu, pakollinen",
                        device_model: "",
                        device_type_id_selected: "Select device type from this select menu, pakollinen",
                        device_name: "",
                        license_id_selected: "Select license from this select menu, vaihtoehtoinen",
                        software_id_selected: "Select software from this select menu, vaihtoehtoinen",
                        buy_date: "",
                        serial_no: "",
                        warranty_id_selected: "Select warranty from this select menu, vaihtoehtoinen",
                        warranty_valid_until: "",
                        teamviewer_id: "",
                        security_software_id_selected: "Select security software from this select menu, vaihtoehtoinen",
                        order_no: "",
                        vendor_id_selected: "Select vendor from this select menu, pakollinen",
                        product_no: "",
                        manufacturer_id_selected: "Select manufacturer from this select menu, pakollinen",
                        backup_solution_id_selected: "Select backup solution from this select menu, vaihtoehtoinen",
                        lease_contract_no: "",
                        active: 1
                    });
                }
                if (me.state.status == 2){
                    me.setState({
                        active: 1,
                        notes: "",
                        name: "",
                        customer_token: "",
                        address: "",
                        contact_person_name: ""
                    });
                }
                if (me.state.status == 3){
                    me.setState({
                        active: 1,
                        notes: "",
                        user_role_id_selected: "Select user role from this select menu, pakollinen",
                        tel: "",
                        name: "",
                        email: "",
                        customer_token_selected: "Select customer from this select menu, vaihtoehtoinen",
                        password: "",
                        password_confirmation: ""
                    });
                }
            },
            error: function(xhr) {
                console.log(xhr);
                //console.log(JSON.stringify(xhr.responseJSON.errors));
                var errors = JSON.stringify(xhr.responseJSON.errors);
                //{xhr.responseJSON.message}
                console.log(errors);
                me.setState({response: <div className="alert alert-danger" role="alert">Tietojen tallennuksessa ongelmia!<br/>Virhe viesti:<br/>{errors}</div>});
                //console.log(xhr.responseJSON.message);
            }
        });
    }

    render() {
        if (this.state.loaded === false) {
            return (<div className="container">
                        <div className="row justify-content-center">
                            <div className="col-md-8">
                                <h4>Loading...</h4>
                            </div>
                        </div>
                    </div>)
        }
        let allUserRoleOptions = this.state.user_role_id_data.map(function(user_role_id){
            return <option key={user_role_id.id} value={user_role_id.id}>{user_role_id.role}</option>;
        });
        let allCustomerOptions = this.state.customer_token_data.map(function(customer_token){
            return <option key={customer_token.id} value={customer_token.id}>{customer_token.name}</option>;
        });
        let allUserOptions = this.state.user_id_data.map(function(user_id){
            return <option key={user_id.id} value={user_id.id}>{user_id.name}</option>;
        });
        let allOperatingSystemOptions = this.state.operating_system_id_data.map(function(operating_system_id){
            return <option key={operating_system_id.id} value={operating_system_id.id}>{operating_system_id.name}</option>;
        });
        let allDeviceTypeOptions = this.state.device_type_id_data.map(function(device_type_id){
            return <option key={device_type_id.id} value={device_type_id.id}>{device_type_id.type}</option>;
        });
        let allLicenseOptions = this.state.license_id_data.map(function(license_id){
            return <option key={license_id.id} value={license_id.id}>{license_id.name}</option>;
        });
        let allSoftwareOptions = this.state.software_id_data.map(function(software_id){
            return <option key={software_id.id} value={software_id.id}>{software_id.items}</option>;
        });
        let allWarrantyOptions = this.state.warranty_id_data.map(function(warranty_id){
            return <option key={warranty_id.id} value={warranty_id.id}>{warranty_id.description}</option>;
        });
        let allSecuritySoftwareOptions = this.state.security_software_id_data.map(function(security_software_id){
            return <option key={security_software_id.id} value={security_software_id.id}>{security_software_id.description}</option>;
        });
        let allVendorOptions = this.state.vendor_id_data.map(function(vendor_id){
            return <option key={vendor_id.id} value={vendor_id.id}>{vendor_id.name}</option>;
        });
        let allManufacturerOptions = this.state.manufacturer_id_data.map(function(manufacturer_id){
            return <option key={manufacturer_id.id} value={manufacturer_id.id}>{manufacturer_id.name}</option>;
        });
        let allBackupSolutionOptions = this.state.backup_solution_id_data.map(function(backup_solution_id){
            return <option key={backup_solution_id.id} value={backup_solution_id.id}>{backup_solution_id.description}</option>;
        });
        
        const { status } = this.state;

        return(
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header font-weight-bold">Add new items</div>
                            <div className="card-body">

                                <h5>Valitse taulu johon lisätään tietoja</h5>
                                <div className="custom-control custom-radio">
                                    <input type="radio" id="addDeviceRadio" name="addNewItemRadio" className="custom-control-input" checked={status === 1} onChange={(e) => this.showSelectedItem(1)}/>
                                    <label className="custom-control-label" htmlFor="addDeviceRadio">Add new device</label>
                                </div>
                                <div className="custom-control custom-radio">
                                    <input type="radio" id="addCustomerRadio" name="addNewItemRadio" className="custom-control-input" checked={status === 2} onChange={(e) => this.showSelectedItem(2)}/>
                                    <label className="custom-control-label" htmlFor="addCustomerRadio">Add new customer</label>
                                </div>
                                <div className="custom-control custom-radio">
                                    <input type="radio" id="addUserRadio" name="addNewItemRadio" className="custom-control-input" checked={status === 3} onChange={(e) => this.showSelectedItem(3)}/>
                                    <label className="custom-control-label" htmlFor="addUserRadio">Add new user</label>
                                </div>
                                <br/>
                                
                                <form id="addNewItemForm-1" className={this.state.showform_1 ? "d-block" : "d-none"}>
                                    <h5>Add new device</h5>
                                    <div className="form-row">
                                        <div className="form-group col-4">
                                            <label htmlFor="device_token">Device token</label>
                                            <input type="text" className="form-control" name="device_token" placeholder="Pakollinen uniikki" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col-8">
                                            <label htmlFor="customer_token_selected">Customer</label>
                                            <select className="custom-select" name="customer_token_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select customer from this select menu, pakollinen</option>
                                                {allCustomerOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col">
                                            <label htmlFor="admin">Admin</label>
                                            <input type="text" className="form-control" name="admin" placeholder="Vaihtoehtoinen" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col">
                                            <label htmlFor="admin_password">Admin password</label>
                                            <input type="text" className="form-control" name="admin_password" placeholder="Vaihtoehtoinen" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col">
                                            <label htmlFor="admin_password_confirmation">Confirm admin password</label>
                                            <input type="text" className="form-control" name="admin_password_confirmation" placeholder="Vaihtoehtoinen" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-6">
                                            <label htmlFor="user_id_selected">User</label>
                                            <select className="custom-select" name="user_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select user from this select menu, pakollinen</option>
                                                {allUserOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-8">
                                            <label htmlFor="operating_system_id_selected">Operating system</label>
                                            <select className="custom-select" name="operating_system_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select operating system from this select menu, pakollinen</option>
                                                {allOperatingSystemOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col">
                                            <label htmlFor="device_model">Device model</label>
                                            <input type="text" className="form-control" name="device_model" placeholder="Pakollinen" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col">
                                            <label htmlFor="device_name">Device name</label>
                                            <input type="text" className="form-control" name="device_name" placeholder="Pakollinen" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-7">
                                            <label htmlFor="device_type_id_selected">Device type</label>
                                            <select className="custom-select" name="device_type_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select device type from this select menu, pakollinen</option>
                                                {allDeviceTypeOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="notes">Notes</label>
                                        <textarea className="form-control" name="notes" rows="3" placeholder="Vaihtoehtoinen" onChange={this.onChangeHandler}></textarea>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-7">
                                            <label htmlFor="license_id_selected">License</label>
                                            <select className="custom-select" name="license_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select license from this select menu, vaihtoehtoinen</option>
                                                {allLicenseOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-7">
                                            <label htmlFor="software_id_selected">Software</label>
                                            <select className="custom-select" name="software_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select software from this select menu, vaihtoehtoinen</option>
                                                {allSoftwareOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-5">
                                            <label htmlFor="buy_date">Buy date (pakollinen)</label>
                                            <input type="date" className="form-control" name="buy_date" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col-7">
                                            <label htmlFor="serial_no">Serial no</label>
                                            <input type="text" className="form-control" name="serial_no" placeholder="Pakollinen" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-7">
                                            <label htmlFor="warranty_id_selected">Warranty</label>
                                            <select className="custom-select" name="warranty_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select warranty from this select menu, vaihtoehtoinen</option>
                                                {allWarrantyOptions}
                                            </select>
                                        </div>
                                        <div className="form-group col-5">
                                            <label htmlFor="warranty_valid_until">Warranty valid until (vaihtoehtoinen)</label>
                                            <input type="date" className="form-control" name="warranty_valid_until" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-4">
                                            <label htmlFor="teamviewer_id">Teamviewer id</label>
                                            <input type="text" className="form-control" name="teamviewer_id" placeholder="Pakollinen" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col-8">
                                            <label htmlFor="security_software_id_selected">Security software</label>
                                            <select className="custom-select" name="security_software_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select security software from this select menu, vaihtoehtoinen</option>
                                                {allSecuritySoftwareOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-5">
                                            <label htmlFor="order_no">Order no</label>
                                            <input type="text" className="form-control" name="order_no" placeholder="Pakollinen" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col-7">
                                            <label htmlFor="vendor_id_selected">Vendor</label>
                                            <select className="custom-select" name="vendor_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select vendor from this select menu, pakollinen</option>
                                                {allVendorOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-5">
                                            <label htmlFor="product_no">Product no</label>
                                            <input type="text" className="form-control" name="product_no" placeholder="Pakollinen" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col-7">
                                            <label htmlFor="manufacturer_id_selected">Manufacturer</label>
                                            <select className="custom-select" name="manufacturer_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select manufacturer from this select menu, pakollinen</option>
                                                {allManufacturerOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-8">
                                            <label htmlFor="backup_solution_id_selected">Backup solution</label>
                                            <select className="custom-select" name="backup_solution_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select backup solution from this select menu, vaihtoehtoinen</option>
                                                {allBackupSolutionOptions}
                                            </select>
                                        </div>
                                        <div className="form-group col-4">
                                            <label htmlFor="lease_contract_no">Lease contract no</label>
                                            <input type="text" className="form-control" name="lease_contract_no" placeholder="Vaihtoehtoinen" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-group form-check"> 
                                        <input className="form-check-input" type="checkbox" id="active1" name="active" onChange={this.onChangeHandler} checked={this.state.active}/>
                                        <label className="form-check-label" htmlFor="active1">Check if active device</label>
                                    </div>
                                    <button className="btn btn-dark" 
                                    type="submit" onClick={this.onSubmitHandler}>Submit</button>
                                </form>

                                <form id="addNewItemForm-2" className={this.state.showform_2 ? "d-block" : "d-none"}>
                                    <h5>Add new customer</h5>
                                    <div className="form-row">
                                        <div className="form-group col-6">
                                            <label htmlFor="customer_token">Customer token</label>
                                            <input type="text" className="form-control" name="customer_token" placeholder="Pakollinen uniikki" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-6">
                                            <label htmlFor="name">Name</label>
                                            <input type="text" className="form-control" name="name" placeholder="Pakollinen, uniikki" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-8">
                                            <label htmlFor="address">Address</label>
                                            <input type="text" className="form-control" name="address" placeholder="Pakollinen" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-6">
                                            <label htmlFor="contact_person_name">Contact person name</label>
                                            <input type="text" className="form-control" name="contact_person_name" placeholder="Vaihtoehtoinen" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="notes">Notes</label>
                                        <textarea className="form-control" name="notes" rows="3" placeholder="Vaihtoehtoinen" onChange={this.onChangeHandler}></textarea>
                                    </div>
                                    <div className="form-group form-check"> 
                                        <input className="form-check-input" type="checkbox" id="active2" name="active" onChange={this.onChangeHandler} checked={this.state.active}/>
                                        <label className="form-check-label" htmlFor="active2">Check if active customer</label>
                                    </div>
                                    <button className="btn btn-dark" type="submit" onClick={this.onSubmitHandler}>Submit</button>
                                </form>

                                <form id="addNewItemForm-3" className={this.state.showform_3 ? "d-block" : "d-none"}>
                                    <h5>Add new user</h5>
                                    <div className="form-row">
                                        <div className="form-group col-5">
                                            <label htmlFor="name">Name</label>
                                            <input type="text" className="form-control" name="name" placeholder="Pakollinen uniikki" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col-7">
                                            <label htmlFor="user_role_id_selected">User role</label>
                                            <select className="custom-select" name="user_role_id_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select user role from this select menu, pakollinen</option>
                                                {allUserRoleOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-5">
                                            <label htmlFor="tel">Phone number</label>
                                            <input type="text" className="form-control" name="tel" placeholder="Vaihtoehtoinen" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col-7">
                                            <label htmlFor="email">Email (huom. kirjautuminen tämän avulla)</label>
                                            <input type="text" className="form-control" name="email" placeholder="Pakollinen, uniikki" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col-8">
                                            <label htmlFor="customer_token_selected">Customer</label>
                                            <select className="custom-select" name="customer_token_selected" value={this.state.value} onChange={this.onChangeHandler}>
                                                <option defaultValue value="">Select customer from this select menu, vaihtoehtoinen</option>
                                                {allCustomerOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="notes">Notes</label>
                                        <textarea className="form-control" name="notes" rows="3" placeholder="Vaihtoehtoinen" onChange={this.onChangeHandler}></textarea>
                                    </div>
                                    <div className="form-row">
                                        <div className="form-group col">
                                            <label htmlFor="password">Password</label>
                                            <input type="text" className="form-control" name="password" placeholder="Pakollinen" onChange={this.onChangeHandler}/>
                                        </div>
                                        <div className="form-group col">
                                            <label htmlFor="password_confirmation">Confirm password</label>
                                            <input type="text" className="form-control" name="password_confirmation" placeholder="Pakollinen" onChange={this.onChangeHandler}/>
                                        </div>
                                    </div>
                                    <div className="form-group form-check"> 
                                        <input className="form-check-input" type="checkbox" id="active3" name="active" onChange={this.onChangeHandler} checked={this.state.active}/>
                                        <label className="form-check-label" htmlFor="active3">Check if active user</label>
                                    </div>
                                    <button className="btn btn-dark" type="submit" onClick={this.onSubmitHandler}>Submit</button>
                                </form>

                                <br/>
                                {this.state.response}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Addnewitem;

if (document.getElementById('addnewitem')) {
    ReactDOM.render(<Addnewitem />, document.getElementById('addnewitem'));
}
