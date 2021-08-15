import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PhonePipe } from './phone-format.pipe';


@NgModule({
  declarations: [ PhonePipe ],
  exports: [ PhonePipe ],
  imports: [
    CommonModule
  ]
})
export class PhoneFormatModule { }
