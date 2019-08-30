package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.dinosoftlabs.foodies.android.Models.AddressListModel;
import com.dinosoftlabs.foodies.android.R;


import java.util.ArrayList;

/**
 * Created by Nabeel on 1/4/2018.
 */

public class AddressListAdapter extends RecyclerView.Adapter<AddressListAdapter.ViewHolder> {

    ArrayList<AddressListModel> getDataAdapter;

    Context context;
    AddressListAdapter.OnItemClickListner onItemClickListner;

    public AddressListAdapter(ArrayList<AddressListModel> getDataAdapter, Context context){
        super();
        this.getDataAdapter = getDataAdapter;
        this.context = context;

    }

    @Override
    public AddressListAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_item_address_list, parent, false);

        AddressListAdapter.ViewHolder viewHolder = new AddressListAdapter.ViewHolder(v);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, final int position) {


        AddressListModel addressListModel = getDataAdapter.get(position);



        String address_str = addressListModel.getStreet()+" "+addressListModel.getCity()+" "+addressListModel.getState();

        holder.address.setText(address_str);

        holder.main_view.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                onItemClickListner.OnItemClicked(v, position);
            }
        });

    }


    @Override
    public int getItemCount() {
        return getDataAdapter.size() ;
    }



    class ViewHolder extends RecyclerView.ViewHolder{

        public TextView address;
        public RelativeLayout main_view;

        public ViewHolder(View itemView) {

            super(itemView);

            address = itemView.findViewById(R.id.address);
            main_view = itemView.findViewById(R.id.address_list_main_div);




        }
    }

    public interface OnItemClickListner {
        void OnItemClicked(View view, int position);
    }

    public void setOnItemClickListner(OnItemClickListner onCardClickListner) {
        this.onItemClickListner = onCardClickListner;
    }
}
