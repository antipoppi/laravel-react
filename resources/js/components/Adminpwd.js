import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class Adminpwd extends Component {
    render() {
        return (
            <span>{this.props.pwd}</span>
        );
    }
}

export default Adminpwd;

if (document.getElementById('adminpwd')) {
    const propsContainer = document.getElementById("adminpwdProps");
    const pwd = Object.assign({}, propsContainer.dataset);
    console.log(pwd);
    ReactDOM.render(<Adminpwd pwd="{pwd}" />, document.getElementById('adminpwd'));
}
