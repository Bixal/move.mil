import React, { Component }  from 'react';

class Conus extends Component {

    handleChange = (isConus) => {
        if(isConus !== this.props.isConus){
            this.props.selectedConusFn(isConus);
        }
    }

    radioComp = () =>{
        return (
            <div className="flex-container  wrapper">
                <div className="flex-item ie-2-col">
                    <div className="flex-container">
                        <div className="flex-item small">
                            <input
                                type="radio"
                                name="conus"
                                id="conus"
                                value="conus"
                                onChange={(e) => this.handleChange(true)}
                                checked={this.props.isConus} />
                        </div>
                        <div className="flex-item">
                            <label for="conus">CONUS</label>
                        </div>
                    </div>
                </div>
                <div className="flex-item ie-2-col">
                    <div className="flex-container">
                        <div className="flex-item small">
                            <input
                                type="radio"
                                name="oconus"
                                id="oconus"
                                value="oconus"
                                onChange={(e) => this.handleChange(false)}
                                checked={!this.props.isConus} />
                        </div>
                        <div className="flex-item">
                            <label for="oconus">OCONUS</label>
                        </div>
                    </div>
                </div>
           </div>
        )
    }

    render() {
        return (
            <div>
                {this.radioComp()}
            </div>
        )
    }
}

export default Conus;
