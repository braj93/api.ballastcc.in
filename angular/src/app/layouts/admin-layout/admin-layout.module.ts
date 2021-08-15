import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AdminLayoutRoutes } from './admin-layout.routing';
import { DashboardComponent, AdminDashboardResolver } from '../../dashboard';
import { UserProfileComponent } from '../../user-profile/user-profile.component';
import { UserEditProfileComponent } from '../../user-profile/user-edit-profile.component';
import { KnowledgebaseComponent, KnowledgebaseAddComponent, KnowledgebaseEditComponent } from '../../admin-component/knowledgebase';
import { TableListComponent } from '../../table-list/table-list.component';
import { TypographyComponent } from '../../typography/typography.component';
import { IconsComponent } from '../../icons/icons.component';
import { NotificationsComponent } from '../../notifications/notifications.component';
import { UpgradeComponent } from '../../upgrade/upgrade.component';
import { MatButtonModule } from '@angular/material/button';
import { MatInputModule } from '@angular/material/input';
import { MatRippleModule } from '@angular/material/core';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatTooltipModule } from '@angular/material/tooltip';
import { MatSelectModule } from '@angular/material/select';
import { SharedModule } from '../../shared';
import { AlertModule  } from 'ngx-bootstrap';
import { BroadcastComponent, AddBroadcastComponent, EditBroadcastComponent, BroadcastDetailByIdResolver } from '../../broadcast';
import { DataTablesModule } from 'angular-datatables';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { NgSelectModule } from '@ng-select/ng-select';

import { UsersResolver } from '../../user-profile/users-resolver.service';
import { KnowledgebaseResolver } from '../../admin-component/knowledgebase/knowlegebase-resolver.service';
import { KnowledgebaseDetailByIdResolver } from '../../admin-component/knowledgebase/knowlegebase-detail-by-id-resolver.service';
import { CrmComponent } from '../../admin-component/crm/crm.component';
import { AgencyBillingComponent } from '../../admin-component/agency-billing/agency-billing.component';
import { CategoryComponent } from '../../admin-component/category/category.component';
import { AddCategoryComponent } from '../../admin-component/category/add-category/add-category.component';
import { EditCategoryComponent } from '../../admin-component/category/edit-category/edit-category.component';
import { CategoryDetailByIdResolver } from '../../admin-component/category/category-resolver.service';
import { PricingPlansComponent, AddPricingPlanComponent, PricingPlanDetailByIdResolver, EditPricingPlanComponent } from '../../admin-component/pricing-plans';
import { Daterangepicker } from 'ng2-daterangepicker';
// import { FroalaEditorModule, FroalaViewModule } from 'angular-froala-wysiwyg';
// import { EditorModule, TINYMCE_SCRIPT_SRC } from '@tinymce/tinymce-angular';
import { QuillModule } from 'ngx-quill';
import { BsDatepickerModule } from 'ngx-bootstrap/datepicker';
import { TimepickerModule } from 'ngx-bootstrap';
import { PhoneFormatModule  } from '../../pipes/phone-format.module';
@NgModule({
  imports: [
    PhoneFormatModule,
    NgSelectModule,
    AlertModule,
    SharedModule,
    DataTablesModule,
    CommonModule,
    RouterModule.forChild(AdminLayoutRoutes),
    FormsModule,
    ReactiveFormsModule,
    MatButtonModule,
    MatRippleModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatTooltipModule,
    NgxDatatableModule,
    Daterangepicker,
    // FroalaEditorModule.forRoot(),
    // FroalaViewModule.forRoot(),
    // EditorModule,
    QuillModule.forRoot(),
    BsDatepickerModule.forRoot(),
    TimepickerModule.forRoot()
  ],
  declarations: [
    DashboardComponent,
    UserProfileComponent,
    TableListComponent,
    TypographyComponent,
    IconsComponent,
    // MapsComponent,
    NotificationsComponent,
    UpgradeComponent,
    AddBroadcastComponent,
    EditBroadcastComponent,
    BroadcastComponent,
    KnowledgebaseComponent,
    KnowledgebaseAddComponent,
    KnowledgebaseEditComponent,
    CrmComponent,
    AgencyBillingComponent,
    CategoryComponent,
    AddCategoryComponent,
    EditCategoryComponent,
    UserEditProfileComponent,
    PricingPlansComponent,
    AddPricingPlanComponent,
    EditPricingPlanComponent
  ],
  providers: [
    PricingPlanDetailByIdResolver,
    AdminDashboardResolver,
    BroadcastDetailByIdResolver,
    UsersResolver,
    KnowledgebaseResolver,
    KnowledgebaseDetailByIdResolver,
    CategoryDetailByIdResolver,
    // { provide: TINYMCE_SCRIPT_SRC, useValue: 'tinymce/tinymce.min.js' }
  ]
})

export class AdminLayoutModule {}
