import {Component} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import * as cmplz_api from "../utils/api";
import Icon from "../utils/Icon";

class Modal extends Component {
    constructor() {
        super( ...arguments );
        this.state = {
            data:[],
            buttonsDisabled:false,
        };
    }

    dismissModal(dropItem){
        this.props.handleModal(false, null, dropItem);
    }
    componentDidMount() {
        this.setState({
            data:this.props.data,
            buttonsDisabled:false,
        });
    }

    handleFix(e){
        //set to disabled
        let action = this.props.data.action;
        this.setState({
            buttonsDisabled:true
        });
        cmplz_api.doAction(action, 'refresh', this.props.data ).then( ( response ) => {
            this.props.data
            let {
                data,
            } = this.state;
            data.description = response.msg;
            data.subtitle = '';
            this.setState({
                data: data,
            });
            let item = this.props.data;
            if (response.success) {
                this.dismissModal(this.props.data);
            }
        });
    }

    render(){
        const {
            data,
            buttonsDisabled,
        } = this.state;
        let disabled = buttonsDisabled ? 'disabled' : '';
        let description = data.description;

        return (
            <div>
                <div className="cmplz-modal-backdrop" onClick={ (e) => this.dismissModal(e) }>&nbsp;</div>
                <div className="cmplz-modal" id="{id}">
                    <div className="cmplz-modal-header">
                        <h2 className="modal-title">
                            {data.title}
                        </h2>
                        <button type="button" className="cmplz-modal-close" data-dismiss="modal" aria-label="Close" onClick={ (e) => this.dismissModal(e) }>
                            <Icon name='times' />
                        </button>
                    </div>
                    <div className="cmplz-modal-content">
                        {data.subtitle && <div className="cmplz-modal-subtitle">{data.subtitle}</div>}
						{ Array.isArray(description) && description.map(
							(s, i) => <div key={i} className="cmplz-modal-description">{s}</div>
						) }
                    </div>
                    <div className="cmplz-modal-footer">
                        { data.edit && <a href={data.edit} target="_blank" rel="noopener noreferrer" className="button button-secondary">{__("Edit", "complianz-gdpr")}</a>}
                        { data.help && <a href={data.help} target="_blank" rel="noopener noreferrer" className="button cmplz-button-help">{__("Help", "complianz-gdpr")}</a>}
                        { (!data.ignored && data.action==='ignore_url') && <button disabled={disabled} className="button button-primary" onClick={ (e) => this.handleFix(e) }>{ __("Ignore", "complianz-gdpr")}</button>}
                        { data.action!=='ignore_url' &&  <button disabled={disabled} className="button button-primary" onClick={ (e) => this.handleFix(e) }>{__("Fix", "complianz-gdpr")}</button> }
                    </div>
                </div>
            </div>
        )
    }
}

export default Modal;
