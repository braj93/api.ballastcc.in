import { Component, Input, OnInit, NgModule } from '@angular/core';
import { AbstractControlDirective, AbstractControl } from '@angular/forms';

@Component({
  selector: 'errors',
  templateUrl: './errors.component.html',
  styleUrls: []
})
export class ErrorsComponent {

  private static readonly errorMessages = {
    'required': () => 'This field is required',
    'minlength': (params) => 'The min number of characters should be ' + params.requiredLength,
    'maxlength': (params) => 'The max allowed number of characters should be ' + params.requiredLength,
    'pattern': (params) => 'The required pattern should be: ' + params.requiredPattern, 
    'email': (params) => params.message
  };

  @Input()
  private control: AbstractControlDirective | AbstractControl;

  showErrors(): boolean {
    return this.control &&
      this.control.errors &&
      (this.control.dirty || this.control.touched);
  }

  errors(): string[] {
    return Object.keys(this.control.errors)
      .map(field => this.getMessage(field, this.control.errors[field]));
  }

  private getMessage(type: string, params: any) {
    return ErrorsComponent.errorMessages[type](params);
  }

}
