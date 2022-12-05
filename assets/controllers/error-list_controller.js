import { Controller } from '@hotwired/stimulus';
import List from 'list.js';

export default class extends Controller {
  connect() {
    const errorList = new List('error-list', {
      valueNames: ['message', 'path', 'count'],
      page: 100,
      pagination: true
    });
  }
}
