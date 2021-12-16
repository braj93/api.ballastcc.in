import { ActivatedRoute } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/core';

@Component({
  selector: 'app-view-payment',
  templateUrl: './view-payment.component.html',
  styleUrls: ['./view-payment.component.scss']
})
export class ViewPaymentComponent implements OnInit {
  public print_data:any="";
  public name ="braj yadav";
  public id:any;
  public FeeDetail:any;
  constructor(
    public route: ActivatedRoute,
    public adminService: AdminService,
  ) { }

  ngOnInit(): void {
    this.id = this.route.snapshot.paramMap.get('id')
    this.print_data =document.getElementById("free-receipt-print");
    let inputData= {
      pay_id:this.id
    }
    this.adminService.getPaymentstDetailById(inputData).subscribe((response: any) => {
      this.FeeDetail=response.data;
      // console.log(this.FeeDetail);
    }, err => {
      this.adminService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }
  
  // printPage(divName:any) {
  //   // var divContents = document.getElementById(divName).innerHTML;
  //   let myContainer = document.getElementById(divName) as HTMLInputElement;
   
  // }

  printPage(divName:any): void {
    let printContents, popupWin;
    printContents = document.getElementById(divName)?.innerHTML;
    console.log(printContents);
    // printContents = document.getElementById(divName) as HTMLInputElement;
    popupWin = window.open('', '_blank');
    popupWin?.document.open();
    popupWin?.document.write(`
      <html>
        <head>
          <title></title>
          <style>
          // table, th, td {
          //   border: 1px solid black;
          //   border-collapse: collapse;
          // }
          </style>
        </head>
    <!---<body onload="window.print();window.close()"> --->
    <body onload="window.print();window.close()">
    <div>
    ${printContents}
    <div style="width: 100%; border: 1px dashed black; margin:30px 0px;"></div>
    ${printContents}
    </div>
    </body>
      </html>`
    );
    popupWin?.document.close();

}
}