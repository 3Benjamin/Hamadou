import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WadabaComponent } from './wadaba.component';

describe('WadabaComponent', () => {
  let component: WadabaComponent;
  let fixture: ComponentFixture<WadabaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WadabaComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WadabaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
