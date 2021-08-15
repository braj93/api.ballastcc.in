import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RealEstateTemplateComponent } from './real-estate-template.component';

describe('RealEstateTemplateComponent', () => {
  let component: RealEstateTemplateComponent;
  let fixture: ComponentFixture<RealEstateTemplateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RealEstateTemplateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RealEstateTemplateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
