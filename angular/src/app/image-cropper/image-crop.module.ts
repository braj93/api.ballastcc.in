import { ModuleWithProviders, NgModule } from '@angular/core';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { RouterModule } from '@angular/router';

import {LyThemeModule,  LY_THEME} from '@alyle/ui';

import { LyResizingCroppingImageModule } from '@alyle/ui/resizing-cropping-images';
import { LyButtonModule } from '@alyle/ui/button';
import { LyIconModule } from '@alyle/ui/icon';
import { LyTypographyModule } from '@alyle/ui/typography';

/** Import theme */
import { MinimaLight, MinimaDark } from '@alyle/ui/themes/minima';

import { ImageCropComponent } from './image-crop.component';
import { ProfileCropComponent } from './profile-crop.component';
import { BannerCropComponent } from './banner-crop.component';
import { VerticalCropComponent } from './vertical-crop.component';
import { SquerCropComponent } from './squer-crop.component';

import { SharedModule } from '../shared';
import { BsDropdownModule } from 'ngx-bootstrap';
@NgModule({
  imports: [
    SharedModule,
    LyResizingCroppingImageModule,
    LyThemeModule.setTheme('minima-dark'), // or minima-light
    LyButtonModule,
    LyIconModule,
    LyTypographyModule,
    BsDropdownModule.forRoot()
  ],
  declarations: [
    ImageCropComponent,
    ProfileCropComponent,
    VerticalCropComponent,
    BannerCropComponent,
    SquerCropComponent
  ],
    exports: [
    ImageCropComponent,
    ProfileCropComponent,
    VerticalCropComponent,
    BannerCropComponent,
    SquerCropComponent
  ],
  providers: [
    {provide: LY_THEME, useClass: MinimaLight, multi: true},
    { provide: LY_THEME, useClass: MinimaDark, multi: true}
  ]
})
export class ImageCropModule {}
