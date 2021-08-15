import { Pipe, PipeTransform } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
@Pipe({
  name: 'domSanitizer'
})
export class DomSanitizerPipe implements PipeTransform {
  constructor(private dom: DomSanitizer) {

  }
  // transform(value: unknown, ...args: unknown[]): unknown {
  //   return null;
  // }

  transform(value: string, ...args) {
    if (value !== undefined) {
      return this.dom.bypassSecurityTrustResourceUrl(value + '?rel=0&autoplay=1&controls=1&showinfo=0&modestbranding=1&enablejsapi=1');
    }
  }

}
