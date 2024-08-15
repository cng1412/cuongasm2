using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using static System.Windows.Forms.VisualStyles.VisualStyleElement.StartPanel;

namespace asm2_attendance
{
    public partial class Admin : Form
    {
        SqlConnectionStringBuilder sqlstrbuilder = new SqlConnectionStringBuilder();
        public Admin()
        {
            InitializeComponent();
            sqlstrbuilder["Server"] = "Cuong\\CUONGDUA";
            sqlstrbuilder["Database"] = "Attendances";
            sqlstrbuilder["Integrated Security"] = "True";
            sqlstrbuilder["Trusted_Connection"] = "Yes";
        }
        
        private void button1_Click(object sender, EventArgs e)
        {

        }

        private void btnupdate_Click(object sender, EventArgs e)
        {
            int userID = Convert.ToInt32(txtuserid.Text);
            string status = txtstatus.Text;
            SqlConnection sqlConnection = GetConnection();
            sqlConnection.Open();
            if (sqlConnection.State == ConnectionState.Open)
            {
                SqlCommand cmd = new SqlCommand();
                cmd.Connection = sqlConnection;
                cmd.CommandText = "UPDATE Attendance SET " +
                                     "Status = @status  " +
                                     "WHERE StudentID = @UserID";
                cmd.Parameters.AddWithValue("status", status);
                cmd.Parameters.AddWithValue("UserID", userID);

                var result = cmd.ExecuteNonQuery();
                if (result == 0)
                {
                    MessageBox.Show("Unable to update user");
                }
                else
                {
                    MessageBox.Show("Updated");
                    LoadData();
                }
            }
            sqlConnection.Close();
        }
        private SqlConnection GetConnection()
        {
            string sql = sqlstrbuilder.ToString();
            var Sqlcon = new SqlConnection(sql);
            try
            {
                if (Sqlcon.State == ConnectionState.Closed)
                {
                    Sqlcon.Open();
                    //MessageBox.Show("Kết Nối Thành Công");
                }
                //LoadData();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Error occurred: " + ex.Message);
            }
            finally
            {
                Sqlcon.Close();
            }
            return Sqlcon;
        }
        private void LoadData()
        {
            string selectedClass = comboBox1.SelectedItem.ToString();
            string sql = sqlstrbuilder.ToString();
            var Sqlcon = new SqlConnection(sql);
            Sqlcon.Open();
            SqlCommand cmd = new SqlCommand();
            cmd.Connection = Sqlcon;
            cmd.CommandText = "SELECT \r\n    A.StudentID,\r\n    A.ClassID,\r\n    A.Status,\r\n    C.ClassName\r\nFROM \r\n    Attendance A\r\nJOIN \r\n    Class C\r\nON \r\n    A.ClassID = C.ClassID\r\nWHERE \r\n    C.ClassName = @className";
            cmd.Parameters.AddWithValue("className", selectedClass);
            var sqlreader = cmd.ExecuteReader();

            var datatable = new DataTable();
            datatable.Load(sqlreader);
            dataGridView1.DataSource = datatable;

            Sqlcon.Close();
        }

        private void dataGridView1_CellClick(object sender, DataGridViewCellEventArgs e)
        {

            int index = dataGridView1.SelectedCells[0].RowIndex;
            int userID = Convert.ToInt32(dataGridView1.Rows[index].Cells[0].Value);
            txtstatus.Text = Convert.ToString(dataGridView1.Rows[index].Cells[2].Value);
            txtuserid.Text = userID.ToString();
        }

        private void comboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            string selectedClass = comboBox1.SelectedItem.ToString();
            SqlConnection sqlConnection = GetConnection();
            sqlConnection.Open();

            try
            {
                SqlCommand cmd = new SqlCommand();
                cmd.Connection = sqlConnection;
                cmd.CommandText = "SELECT \r\n    A.StudentID,\r\n    A.ClassID,\r\n    A.Status,\r\n    C.ClassName\r\nFROM \r\n    Attendance A\r\nJOIN \r\n    Class C\r\nON \r\n    A.ClassID = C.ClassID\r\nWHERE \r\n    C.ClassName = @className ";
                cmd.Parameters.AddWithValue("className", selectedClass);
                SqlDataAdapter dataAdapter = new SqlDataAdapter(cmd);
                System.Data.DataTable dataTable = new System.Data.DataTable();

                dataAdapter.Fill(dataTable);

                dataGridView1.DataSource = dataTable;
                
            }
            catch (Exception ex)
            {
                MessageBox.Show("Error occurred: " + ex.Message);
            }
        }

        private void Admin_Load(object sender, EventArgs e)
        {
            txtuserid.Text = "";
            SqlConnection sqlConnection = GetConnection();
            sqlConnection.Open();
            try
            {
                SqlCommand cmd = new SqlCommand();
                cmd.Connection = sqlConnection;
                cmd.CommandText = "SELECT CLassName FROM Class ";

                SqlDataReader reader = cmd.ExecuteReader();

                while (reader.Read())
                {
                    string className = reader["CLassName"].ToString();
                    comboBox1.Items.Add(className);
                }

                reader.Close();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Error occurred: " + ex.Message);
            }
        }

        private void button5_Click(object sender, EventArgs e)
        {
            DialogResult result = MessageBox.Show("Are you sure you want to exit?", "Confirm exit", MessageBoxButtons.YesNo, MessageBoxIcon.Question);

            if (result == DialogResult.Yes)
            {
                //    Login form2 = new Login();
                //    form2.Show();
                //    this.Close();
                this.Hide();

                Login formlogin = new Login();
                formlogin.ShowDialog();

                this.Close();
            }
        }

        private void button1_Click_1(object sender, EventArgs e)
        {

        }
    }
}
