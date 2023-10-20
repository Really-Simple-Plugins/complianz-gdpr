import { __ } from '@wordpress/i18n';
const ChangeStatus = (props) => {
	let statusClass = props.item.status==1 ? 'button button-primary cmplz-status-allowed' : 'button button-default cmplz-status-revoked';
	let label = props.item.status==1 ? __("Revoke", "complianz-gdpr") : __("Allow", "complianz-gdpr");
	return (
		<button onClick={ () => props.onChangeHandlerDataTableStatus( props.item.status, props.item, 'status' ) } className={statusClass}>{label}</button>
	)
}
export default ChangeStatus
