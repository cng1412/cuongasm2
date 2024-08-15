using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using static System.Windows.Forms.VisualStyles.VisualStyleElement.StartPanel;

namespace asm2_attendance
{
    public partial class Forgot_pass : Form
    {
        SqlConnectionStringBuilder sqlstrbuilder = new SqlConnectionStringBuilder();
        public Forgot_pass()
        {
            InitializeComponent();
            sqlstrbuilder["Server"] = "LAPTOP-DED5LD2D\\SQLEXPRESS";
            sqlstrbuilder["Database"] = "Attendance";
            sqlstrbuilder["Integrated Security"] = "True";
            sqlstrbuilder["Trusted_Connection"] = "Yes";
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

        private void btnshowpass_Click(object sender, EventArgs e)
        {
            string phoneNumber = txtphone.Text;

            SqlConnection sqlConnection = GetConnection();
            sqlConnection.Open();

            SqlCommand cmd = new SqlCommand();
            cmd.Connection = sqlConnection;
            cmd.CommandText = "SELECT Password FROM Users WHERE phone = @phoneNumber";
            cmd.Parameters.AddWithValue("@phoneNumber", phoneNumber);
            try
            {
                var bien_tam = cmd.ExecuteScalar();
                var password = Convert.ToString(bien_tam);

                if (!string.IsNullOrEmpty(password))
                {
                    MessageBox.Show("Your password is: " + password);

                }
                else
                {
                    MessageBox.Show("Phone number does not exist. Please re-enter.");
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Error occurred: " + ex.Message);
            }
            finally 
            { 
                sqlConnection.Close(); 
            }
        }

        private void label1_Click(object sender, EventArgs e)
        {

        }
    }
}

