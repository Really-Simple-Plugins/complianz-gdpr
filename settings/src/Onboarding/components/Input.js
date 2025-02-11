const OnboardingInput = ({ type, className = '', error = '', ...otherProps }) => {

	if (!type) return;

	if (type === 'email') return (
		<div className={`cmplz-websitescan-input-wrapper ${type}`}>
			<input className={`${className} cmplz-websitescan-input`} type="email" {...otherProps} />
			{error && <span className='cmplz-websitescan-input-invalid'>{error}</span>}
		</div>
	)

	if (type === 'checkbox') return (
		<div className={`cmplz-websitescan-input-wrapper ${type}`}>
			<input className={`${className} cmplz-websitescan-input`} type="checkbox" {...otherProps} />
		</div>
	)

	return null;
}
export default OnboardingInput;
