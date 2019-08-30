package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;

import com.dinosoftlabs.foodies.android.R;


/**
 * Created by Nabeel on 12/18/2017.
 */

public class CartReportFragment extends Fragment {

    private ImageView back_icon;
    private RelativeLayout add_payment_method_div,delivery_address_div;

    public static boolean FLAG_ORDER_REAL_DATA,FLAG_DEAL_REAL_DATA;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.cart_fragment, container, false);

        initUI(view);

        return view;


    }

    private void initUI(View v){

        back_icon = v.findViewById(R.id.back_icon_cart_report);
        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if(RestaurantsFragment.FLAG_Restaurant_FRAGMENT) {
                    RestaurantsFragment restaurantsFragment = new RestaurantsFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.cart_main_container, restaurantsFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    RestaurantsFragment.FLAG_Restaurant_FRAGMENT = false;

                }

                else if(OrdersFragment.FLAG_CART_ORDER_FRAGMENT){


                        OrdersFragment ordersFragment = new OrdersFragment();
                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                        transaction.replace(R.id.cart_main_container, ordersFragment);
                        transaction.addToBackStack(null);
                        transaction.commit();
                        OrdersFragment.FLAG_CART_ORDER_FRAGMENT = false;
                        FLAG_ORDER_REAL_DATA = true;


                }

                else if(DealsFragment.FLAG_CART_DEALS_FRAGMENT){
                    DealsFragment dealsFragment = new DealsFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.cart_main_container, dealsFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    DealsFragment.FLAG_CART_DEALS_FRAGMENT = false;
                    FLAG_DEAL_REAL_DATA = true;


                }

                else if(UserAccountFragment.FLAG_CART_USER_FRAGMENT){
                    UserAccountFragment userAccountFragment = new UserAccountFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.cart_main_container, userAccountFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    UserAccountFragment.FLAG_CART_USER_FRAGMENT = false;

                }

            }
        });

        add_payment_method_div = v.findViewById(R.id.add_payment_method_div);
        add_payment_method_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

               getContext().startActivity(new Intent(getContext(),PaymentMethodActivity.class));

            }
        });

        delivery_address_div = v.findViewById(R.id.delivery_address_div);
        delivery_address_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getContext().startActivity(new Intent(getContext(),AddAddressActivity.class));
            }
        });

    }
}
