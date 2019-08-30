package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.RelativeLayout;

import com.dinosoftlabs.foodies.android.R;


public class AddAddressActivity extends AppCompatActivity {

    RelativeLayout  add_address_div,add_address_layout,select_address_layout;
    Button cancle_address_btn,cancle_add_address_btn;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_address);
        initUI();
    }

    private void initUI(){
        add_address_div = findViewById(R.id.add_address_div);
        select_address_layout = findViewById(R.id.select_add_layout);
        add_address_layout = findViewById(R.id.add_address_layout);
        cancle_add_address_btn = findViewById(R.id.cancle_add_address_btn);
        add_address_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                select_address_layout.setVisibility(View.GONE);
                add_address_layout.setVisibility(View.VISIBLE);

            }
        });


        cancle_add_address_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                select_address_layout.setVisibility(View.VISIBLE);
                add_address_layout.setVisibility(View.GONE);
            }
        });



        cancle_address_btn = findViewById(R.id.cancle_address_btn);
        cancle_address_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });
    }
}
