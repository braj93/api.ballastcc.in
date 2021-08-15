import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { RouterModule } from '@angular/router';

// backend import
import { CoreModule } from './core/core.module';
// backend import

import {MatDatepickerModule} from '@angular/material/datepicker';

import { AppRoutingModule } from './app.routing';
import { ComponentsModule } from './components/components.module';
import { CommonModule } from '@angular/common';
import { AppComponent } from './app.component';


import { DashboardComponent } from './dashboard/dashboard.component';
// import { UserProfileComponent } from './user-profile/user-profile.component';
import { TableListComponent } from './table-list/table-list.component';
import { TypographyComponent } from './typography/typography.component';
import { IconsComponent } from './icons/icons.component';
// import { MapsComponent } from './maps/maps.component';
import { NotificationsComponent } from './notifications/notifications.component';
import { UpgradeComponent } from './upgrade/upgrade.component';
import {
  AgmCoreModule
} from '@agm/core';
import { AdminLayoutComponent } from './layouts/admin-layout/admin-layout.component';
import { UserLayoutComponent } from './layouts/user-layout/user-layout.component';
import { LoginComponent } from './login/login.component';

import { HttpClientModule } from '@angular/common/http';
import { SharedModule } from './shared';
import { AlertModule } from 'ngx-bootstrap';
import { NgxStripeModule } from 'ngx-stripe';
import { environment } from '../environments/environment';
import {
  LyThemeModule,
  LY_THEME
} from '@alyle/ui';
/** Import theme */
import { MinimaLight, MinimaDark } from '@alyle/ui/themes/minima';
import { ImageCropModule } from './image-cropper/image-crop.module';
@NgModule({
  imports: [
    ImageCropModule,
    NgxStripeModule.forRoot(environment.stripe_pk),
    SharedModule,
    HttpClientModule,
    CommonModule,
    CoreModule,
    BrowserAnimationsModule,
    FormsModule,
    ReactiveFormsModule,
    HttpModule,
    ComponentsModule,
    RouterModule,
    AppRoutingModule,
    AlertModule.forRoot(),
    AgmCoreModule.forRoot({
      apiKey: 'YOUR_GOOGLE_MAPS_API_KEY'
    })
  ],
  declarations: [
    AppComponent,
    AdminLayoutComponent,
    UserLayoutComponent,
    LoginComponent
  ],
  providers: [{provide: LY_THEME, useClass: MinimaLight, multi: true},
    { provide: LY_THEME, useClass: MinimaDark, multi: true}],
  bootstrap: [AppComponent]
})
export class AppModule { }
