import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {  ErrorsComponent, EmailValidatorDirective} from './validations';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { BsDatepickerModule } from 'ngx-bootstrap/datepicker';
import { HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';
// import { BsDropdownModule, ModalModule } from 'ngx-bootstrap';
// import { AlertModule } from 'ngx-bootstrap';

@NgModule({
  declarations: [
    ErrorsComponent,
    EmailValidatorDirective
  ],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule,
    // ModalModule.forRoot(),
    // AlertModule.forRoot(),
    BsDatepickerModule.forRoot(),
  ],exports:[
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule,
    ErrorsComponent,
  ]
})
export class SharedModule { }
