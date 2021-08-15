import {AbstractControl} from '@angular/forms';
export class PasswordValidation {

    static MatchPassword(AC: AbstractControl) {
       let password = AC.get('password').value; // to get value in input tag
       let confirmPassword = AC.get('confirmPassword').value; // to get value in input tag
       if(confirmPassword != ''){
            if(confirmPassword != password ) {
                AC.get('confirmPassword').setErrors( {MatchPassword: true} )
            } else { 
                return null
            }
        }
    }
}

export class EmailValidation {

    static MatchEmail(AC: AbstractControl) {
       let email = AC.get('email').value; // to get value in input tag
       let confirm_email = AC.get('confirm_email').value; // to get value in input tag
       if(confirm_email != ''){
            if(confirm_email != email ) {
                AC.get('confirm_email').setErrors( {MatchEmail: true} )
            } else { 
                return null
            }
        }
    }
}