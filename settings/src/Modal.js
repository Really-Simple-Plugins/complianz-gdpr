import Dialog from '@mui/material/Dialog';
import DialogTitle from '@mui/material/DialogTitle';

const Modal = ({isOpen, title, onClose, children}) => {
  return (
      <Dialog className='cmplz-modal' open={isOpen} onClose={onClose} sx={{ borderRadius: 'var(--rsp-border-radius)' }}>
        <DialogTitle>
          {title}
        </DialogTitle>
        {children}
      </Dialog>
  );
};

export default Modal;
