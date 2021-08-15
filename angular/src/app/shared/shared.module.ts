import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';

import { BrowseButtonComponent } from './buttons';
import {  ErrorsComponent, EmailValidatorDirective} from './validations';
import { ShowAuthedDirective } from './show-authed.directive';
import { BsDropdownModule, ModalModule } from 'ngx-bootstrap';
import { AlertModule } from 'ngx-bootstrap';
// import { NgSelectModule } from '@ng-select/ng-select';
import { InviteUsersModalComponent } from './invite-user-modal';
import { CrmAddContactModalComponent } from './crm-add-contact-modal';
import { CrmAdminBulkContactModalComponent } from './crm-admin-bulk-contact-modal';
import { CrmEditContactModalComponent } from './crm-edit-contact-modal';
import { ContentEditModalComponent } from './content-edit-modal';
import { AddMemberModalComponent } from './add-member';
import { QuillModule } from 'ngx-quill';
import { BsDatepickerModule } from 'ngx-bootstrap/datepicker';
@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule,
    BsDropdownModule.forRoot(),
    ModalModule.forRoot(),
    AlertModule.forRoot(),
    QuillModule.forRoot(),
    BsDatepickerModule.forRoot(),
    // NgSelectModule
  ],
  declarations: [
    BrowseButtonComponent,
    ErrorsComponent,
    EmailValidatorDirective,
    ShowAuthedDirective,
    InviteUsersModalComponent,
    CrmAddContactModalComponent,
    CrmEditContactModalComponent,
    CrmAdminBulkContactModalComponent,
    ContentEditModalComponent,
    AddMemberModalComponent
  ],

  exports: [
    CommonModule,
    BrowseButtonComponent,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule,
    ErrorsComponent,
    EmailValidatorDirective,
    ShowAuthedDirective,
    InviteUsersModalComponent,
    CrmAddContactModalComponent,
    CrmEditContactModalComponent,
    CrmAdminBulkContactModalComponent,
    ContentEditModalComponent,
    AddMemberModalComponent
  ],
  entryComponents: [
    InviteUsersModalComponent,
    CrmAddContactModalComponent,
    CrmEditContactModalComponent,
    CrmAdminBulkContactModalComponent,
    ContentEditModalComponent,
    AddMemberModalComponent
]
})
export class SharedModule {}
