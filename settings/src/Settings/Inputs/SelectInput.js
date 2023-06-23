import {memo} from 'react';
import * as Select from '@radix-ui/react-select';
import Icon from '../../utils/Icon';
import './Input.scss';
import './SelectInput.scss';
import { __ } from '@wordpress/i18n';

const SelectInput = ({
	value = false,
	onChange,
	required,
	defaultValue,
	disabled,
	options = {},
	 innerRef,
}) => {
	return (
		<div className="cmplz-input-group cmplz-select-group">
			<Select.Root
				//ref={innerRef}
				value={value}
				defaultValue={defaultValue}
				onValueChange={onChange}
				required={required}
				disabled={disabled && !Array.isArray(disabled)}
			>
				<Select.Trigger className="cmplz-select-group__trigger">
					<Select.Value/>
					<Icon name={'chevron-down'}/>
				</Select.Trigger>
					<Select.Content
						className="cmplz-select-group__content"
						position="popper"
					>
						<Select.ScrollUpButton
							className="cmplz-select-group__scroll-button">
							<Icon name={'chevron-up'}/>
						</Select.ScrollUpButton>
						<Select.Viewport className="cmplz-select-group__viewport">
							<Select.Group>
								<Select.Item className={'cmplz-select-group__item'} key={0} value="">
									<Select.ItemText>{__("Select an option","complianz-gdpr")}</Select.ItemText>
								</Select.Item>
								{Object.entries(options).map(([optionValue, optionText]) => (
									<Select.Item
										disabled={Array.isArray(disabled) && disabled.includes(optionValue) }
										className={'cmplz-select-group__item'}
										key={optionValue}
										value={optionValue}>
										<Select.ItemText>{optionText}</Select.ItemText>
									</Select.Item>
								))}
							</Select.Group>
						</Select.Viewport>
						<Select.ScrollDownButton
							className="cmplz-select-group__scroll-button">
							<Icon name={'chevron-down'}/>
						</Select.ScrollDownButton>
					</Select.Content>
			</Select.Root>
		</div>
	);
};

export default memo(SelectInput);
