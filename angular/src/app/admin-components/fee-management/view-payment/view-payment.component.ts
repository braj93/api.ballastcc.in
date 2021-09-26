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
  constructor(
    public route: ActivatedRoute,
    public adminService: AdminService,
  ) { }

  ngOnInit(): void {
    this.print_data =document.getElementById("free-receipt-print");
  }
  
  printPage() {
    
    window.print();
  }

}
