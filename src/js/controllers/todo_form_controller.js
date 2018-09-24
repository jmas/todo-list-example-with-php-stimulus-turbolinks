import { Controller } from 'stimulus';
import Turbolinks from 'turbolinks';

export default class extends Controller {
  submit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch(event.target.action, {
      method: event.target.method,
      body: formData,
      redirect: 'manual'
    }).then(() => Turbolinks.visit('/', { action: 'replace' }));
  }
}
