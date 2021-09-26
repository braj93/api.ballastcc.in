import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AdminLayoutRoutingModule } from './admin-layout-routing.module';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import {DashboardComponent} from '../../admin-components/dashboard';
import { SharedModule } from '../../shared';
import { AlertModule } from 'ngx-bootstrap/alert';
import { FormsModule,ReactiveFormsModule } from '@angular/forms';
import {StudentComponent,AddStudentComponent,ViewStudentComponent,EditStudentComponent} from '../../admin-components/student';
import { BsModalService,ModalModule, BsModalRef } from 'ngx-bootstrap/modal';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { MatInputModule } from '@angular/material/input';
import { MatRippleModule } from '@angular/material/core';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatTooltipModule } from '@angular/material/tooltip';
import { NgSelectModule } from '@ng-select/ng-select';
// import { DataTablesModule } from 'angular-datatables';
import { BsDatepickerModule } from 'ngx-bootstrap/datepicker';
@NgModule({
  declarations: [
    DashboardComponent,
    StudentComponent,
    AddStudentComponent,
    ViewStudentComponent,
    EditStudentComponent,
  ],
  imports: [
    CommonModule,
    AdminLayoutRoutingModule,
    FormsModule,
    NgxDatatableModule,
    ReactiveFormsModule,
    MatSelectModule,
    MatButtonModule,
    MatInputModule,
    MatRippleModule,
    MatFormFieldModule,
    MatTooltipModule,
    SharedModule,
    // DataTablesModule,
    NgSelectModule,
    BsDatepickerModule.forRoot(),
    ModalModule.forRoot(),
    AlertModule.forRoot(),
  ],
  providers: [BsModalService,BsModalRef],
})
export class AdminLayoutModule { }
