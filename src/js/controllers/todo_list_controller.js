import { Controller } from "stimulus"
import Turbolinks from 'turbolinks';

export default class extends Controller {
    delete(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch(event.target.action, {
            method: event.target.method,
            body: formData
        }).then(response => {
            Turbolinks.visit("/", { action: "replace" });
        });
    }
}
