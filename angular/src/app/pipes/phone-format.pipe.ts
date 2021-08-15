import { Pipe, PipeTransform } from '@angular/core';
import { parsePhoneNumber, CountryCode } from 'libphonenumber-js/min';

@Pipe({
  name: 'phone'
})
export class PhonePipe implements PipeTransform {

  transform(phoneValue: any | string, country: string): any {
    try {
      // const phoneNumber = parsePhoneNumber(phoneValue + '', country as CountryCode);
      // return phoneNumber.number;
      const f_val = phoneValue.replace(/\D[^\.]/g, '');
      // const str = '(' + f_val.slice(0, 3) + ') ' + f_val.slice(3, 6) + '-' + f_val.slice(6);
      const str = f_val.slice(0, 3) + '-' + f_val.slice(3, 6) + '-' + f_val.slice(6);
      return str;
      // return phoneNumber.formatNational();
    } catch (error) {
      return phoneValue;
    }
  }
  // transform(rawNum: any | string, country: string): any {
  //   rawNum = rawNum.charAt(0) !== 0 ? 0 + rawNum : '' + rawNum;

  //   let newStr = '';
  //   let i = 0;

  //   for (; i < Math.floor(rawNum.length / 2) - 1; i++) {
  //     newStr = newStr + rawNum.substr(i * 2, 2) + '-';
  //   }

  //   return newStr + rawNum.substr(i * 2);
  // }
}
