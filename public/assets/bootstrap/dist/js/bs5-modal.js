// bootstrap-modal.js

class Modal {
    constructor(element, options) {
        this._element = element;
        this._options = this._getConfig(options);
        this._dialog = this._element.querySelector('.modal-dialog');
        this._backdrop = null;
        this._isShown = false;
        this._isTransitioning = false;

        this._addEventListeners();
    }

    show() {
        if (this._isShown || this._isTransitioning) {
            return;
        }

        const showEvent = new CustomEvent('show.bs.modal', {
            detail: { relatedTarget: this._relatedTarget }
        });
        this._element.dispatchEvent(showEvent);

        if (this._isShown || showEvent.defaultPrevented) {
            return;
        }

        this._isShown = true;

        this._adjustDialog();

        document.body.classList.add('modal-open');

        this._setEscapeEvent();

        this._element.style.display = 'block';
        this._element.removeAttribute('aria-hidden');
        this._element.setAttribute('aria-modal', true);

        this._showBackdrop(() => this._showElement());
    }

    hide() {
        if (!this._isShown || this._isTransitioning) {
            return;
        }

        const hideEvent = new CustomEvent('hide.bs.modal');
        this._element.dispatchEvent(hideEvent);

        if (!this._isShown || hideEvent.defaultPrevented) {
            return;
        }

        this._isShown = false;

        this._element.classList.remove('show');

        document.removeEventListener('focusin', this._enforceFocus);

        this._element.removeEventListener('click.dismiss.bs.modal', this._hideModal);

        this._hideModal();
    }

    _showElement(callback) {
        this._element.classList.add('show');
        this._element.addEventListener('transitionend', callback);
    }

    _hideModal() {
        this._element.style.display = 'none';
        this._element.setAttribute('aria-hidden', true);
        this._element.removeAttribute('aria-modal');
        document.body.classList.remove('modal-open');
        this._resetAdjustments();
        const hiddenEvent = new CustomEvent('hidden.bs.modal');
        this._element.dispatchEvent(hiddenEvent);
    }

    _addEventListeners() {
        this._element.addEventListener('click', (event) => {
            if (event.target === this._element) {
                this.hide();
            }
        });
    }

    _getConfig(config) {
        return { ...{ backdrop: true, keyboard: true }, ...config };
    }

    _adjustDialog() {
        if (this._isShown) {
            this._element.style.paddingRight = '0px';
        }
    }

    _resetAdjustments() {
        this._element.style.paddingRight = '';
    }

    _setEscapeEvent() {
        if (this._isShown && this._options.keyboard) {
            this._element.addEventListener('keydown.dismiss.bs.modal', (event) => {
                if (event.key === 'Escape') {
                    this.hide();
                }
            });
        } else if (!this._isShown) {
            this._element.removeEventListener('keydown.dismiss.bs.modal', this.hide);
        }
    }

    _showBackdrop(callback) {
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop';
        document.body.appendChild(backdrop);

        backdrop.classList.add('show');

        if (callback) {
            callback();
        }
    }
}

document.querySelectorAll('[data-bs-toggle="modal"]').forEach((button) => {
    const target = document.querySelector(button.getAttribute('data-bs-target'));
    const modalInstance = new Modal(target);

    button.addEventListener('click', () => {
        modalInstance.show();
    });

    target.querySelector('[data-bs-dismiss="modal"]').addEventListener('click', () => {
        modalInstance.hide();
    });
});
