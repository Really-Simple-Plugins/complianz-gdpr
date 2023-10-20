import React, { Component } from 'react';
import PropTypes from 'prop-types';

class ErrorBoundary extends Component {
	constructor(props) {
		super(props);
		this.state = { hasError: false, error: null, errorInfo: null };
		this.resetError = this.resetError.bind(this);
	}

	static getDerivedStateFromError(error) {
		return { hasError: true };
	}


	componentDidCatch(error, errorInfo) {
		this.setState({ error, errorInfo });
		// You can also log the error to an error reporting service
		console.log('ErrorBoundary', error, errorInfo);
	}


	resetError() {
		this.setState({ hasError: false, error: null, errorInfo: null });
	}

	render() {
		if (this.state.hasError) {
			return (
				<div className="cmplz-error-boundary">
					<h3 className="cmplz-h4">Something went wrong.</h3>

					{/* You can render any custom fallback UI */}
					<p>{this.props.fallback}</p>
					<button className="button button-primary" onClick={(e) => function() {window.location.reload()}} >Try Again</button>
				</div>
			);
		}

		return this.props.children;
	}
}

ErrorBoundary.propTypes = {
	children: PropTypes.node,
	fallback: PropTypes.node,
};

export default ErrorBoundary;
