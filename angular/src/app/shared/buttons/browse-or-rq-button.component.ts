import { Component, EventEmitter, Input, Output } from '@angular/core';
import { Router } from '@angular/router'; 
import { concatMap ,  tap } from 'rxjs/operators';
import { of } from 'rxjs';

@Component({
  selector: 'browse-or-rq-button',
  templateUrl: './browse-or-rq-button.component.html'
})
export class BrowseButtonComponent {
  constructor() {}
 
}
